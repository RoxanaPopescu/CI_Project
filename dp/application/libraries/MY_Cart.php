<?php

class MY_Cart extends CI_Cart {
    function  __construct() {
        parent::__construct();
        $this->product_name_rules = '\,\(\)\"\'\.\:\-_ a-z0-9';
    }

    function  update($items = array()) {

        // Was any cart data passed?
		if ( ! is_array($items) OR count($items) == 0)
		{
			return FALSE;
		}

		// You can either update a single product using a one-dimensional array,
		// or multiple products using a multi-dimensional one.  The way we
		// determine the array type is by looking for a required array key named "id".
		// If it's not found we assume it's a multi-dimensional array
		$save_cart = FALSE;
		if (isset($items['rowid']) AND isset($items['qty']) AND isset($items['options']['current_qty']))
		{
			if ($this->_update($items) == TRUE)
			{
				$save_cart = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) AND isset($val['rowid']) AND isset($val['qty']) AND isset($items['options']['current_qty']))
				{
					if ($this->_update($val) == TRUE)
					{
						$save_cart = TRUE;
					}
				}
			}
		}

		// Save the cart data if the insert was successful
		if ($save_cart == TRUE)
		{
			$this->_save_cart();
			return TRUE;
		}

		return FALSE;

    }

    function _update($items = array())
	{
		// Without these array indexes there is nothing we can do
		if ( ! isset($items['qty']) OR ! isset($items['rowid']) OR ! isset($items['options']['current_qty']) OR ! isset($this->_cart_contents[$items['rowid']]))
		{
			return FALSE;
		}

		// Prep the quantity
		$items['qty'] = preg_replace('/([^0-9])/i', '', $items['qty']);

		// Is the quantity a number?
		if ( ! is_numeric($items['qty']))
		{
			return FALSE;
		}

		// Is the new quantity different than what is already saved in the cart?
		// If it's the same there's nothing to do
		if ($this->_cart_contents[$items['rowid']]['qty'] == $items['qty'])
		{
			return FALSE;
		}

                if ($this->_cart_contents[$items['rowid']]['options']['current_qty'] == $items['options']['current_qty'])
		{
			return FALSE;
		}

		// Is the quantity zero?  If so we will remove the item from the cart.
		// If the quantity is greater than zero we are updating
		if ($items['qty'] == 0)
		{
			unset($this->_cart_contents[$items['rowid']]);
		}
		else
		{
			$this->_cart_contents[$items['rowid']]['qty'] = $items['qty'];
                        $this->_cart_contents[$items['rowid']]['options']['current_qty'] = $items['options']['current_qty'];
		}

		return TRUE;
	}

        function _save_cart()
	{
		// Unset these so our total can be calculated correctly below
		unset($this->_cart_contents['total_items']);
		unset($this->_cart_contents['cart_total']);

		// Lets add up the individual prices and set the cart sub-total
		$total = 0;
		$items = 0;
		foreach ($this->_cart_contents as $key => $val)
		{
			// We make sure the array contains the proper indexes
			if ( ! is_array($val) OR ! isset($val['price']) OR ! isset($val['qty']) OR ! isset($val['options']['current_qty']))
			{
				continue;
			}

			$total += ($val['price'] * $val['qty']);
			$items += $val['qty'];

			// Set the subtotal
			$this->_cart_contents[$key]['subtotal'] = ($this->_cart_contents[$key]['price'] * $this->_cart_contents[$key]['qty']);
		}

		// Set the cart total and total items.
		$this->_cart_contents['total_items'] = $items;
		$this->_cart_contents['cart_total'] = $total;

		// Is our cart empty?  If so we delete it from the session
		if (count($this->_cart_contents) <= 2)
		{
			$this->CI->session->unset_userdata('cart_contents');

			// Nothing more to do... coffee time!
			return FALSE;
		}

		// If we made it this far it means that our cart has data.
		// Let's pass it to the Session class so it can be stored
		$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));

		// Woot!
		return TRUE;
	}

}
