<?php

class User_model extends CI_Model {

	function verify_and_get_user($email, $password) {
		$this->db->select('user_id, first_name, last_name, phone, email, user_level');
		$this->db->where('email', $email);
		$this->db->where('password', $password);
		
		$query = $this->db->get('users');
	
		if ($query->num_rows() == 1) {
			//use reference code and dev message for logging/error handling here if needed
			$data['is_true'] = TRUE;
			$data['query_result'] = $query->result();
                        /*$data['message'] = $this->config->item('welcome_message').
                                           $data['query_result'][0]->first_name.'!';*/
			return $data;
		} elseif ($query->num_rows() == 0) {
			$data['is_true'] = FALSE;
			$data['message'] = 'The email and password didn\'t match.';
			return $data;
		} elseif ($query->num_rows() > 1) {
			$data['is_true'] = FALSE;
			$data['message'] = 'Something went wrong! Please try again or contact the site administrator.';
			//$data['reference_code'] = '003';
			//$data['dev_message'] = 'The username and password match more than once in the database.';
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Something went wrong! Please try again, if the problem persists, contact a site administrator.';
			//$data['reference_code'] = '004';
			//$data['dev_message'] = 'Database, script, or site failure.';
			return $data;
		}
	}

	function check_email_exist($email) {
		$this->db->select('email');
		$this->db->where('email', $email);
		
		$query = $this->db->get('users');
		
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

        /* get the address of the user with the user id = $user_id*/
        public function getUserAddress($user_id)
        {
            $this->db->select('address')
                     ->from('users')
                     ->where('user_id', $user_id);

            $query = $this->db->get();

            return $query->result();
            
        }

        /* update the address with the value $address for the user with the id = $user_id */
        public function updateAddress($user_id, $address){

            $data = array(
                           'address' => $address
                         );
            $this->db->update('users', $data, "user_id = $user_id");
        }

        /* insert the new order in the orders table, for the user with the id = $user_id */
        public function insertOrder($user_id)
        {
            $data = array(
                           'user_id' => $user_id
                         );

            $this->db->insert('orders', $data);
        }

        /* get all the orders the user with id = $user_id, has
         * and order them by date in a descendent order */
        public function getOrders($user_id)
        {
            $this->db->select('order_id, total_price, date')
                     ->from('orders')
                     ->where('user_id', $user_id)
                     ->order_by('date', 'desc');

            $query = $this->db->get();

            return $query->result();
        }

        /* check if the order with the id = $order_id belongs to the user with the id = $user_id */
        public function userOrderMatch($user_id, $order_id)
        {
            $this->db->select('user_id', 'order_id')
                     ->from('orders')
                     ->where('user_id', $user_id)
                     ->where('order_id', $order_id);

            $query = $this->db->get();

            if($query->num_rows > 0){
                return true;
            }

            return false;
        }


	function create_user($new_user_array) {
		$query = $this->db->insert('user', $new_user_array);

		if ($query == TRUE) {
			$data['is_true'] = TRUE;
			$data['message'] = 'A new user has been successfully created.';
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Failed to create user! Please try again, if the problem persists, contact a site admin.';
			return $data;
		}
	}
	
	//get users
	function get_user() {
		$this->db->select('user_id, username');
		
		$query = $this->db->get('user');
		
		if ($query->num_rows() > 0) {
			$data['is_true'] = TRUE;
			$data['query_result'] = $query->result();
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Failed to get users.';
			return $data;
		}
	}
	
	//get user
	function get_user_by_username($username) {
		$this->db->select('user_id, username, first_name, last_name, user_level');
		$this->db->where('username', $username);
		$this->db->limit(1);
		
		$query = $this->db->get('user');
		
		if ($query == TRUE) {
			$data['is_true'] = TRUE;
			$data['query_result'] = $query->result();
			$data['message'] = 'Successfully retrieved user';
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Failed to retrieved user';
			return $data;
		}
	}
	
	function verify_password($user_id, $password) {
		$this->db->select('user_id', 'password');
		$this->db->where('user_id', $user_id);
		$this->db->where('password', $password);
		
		$query = $this->db->get('user');
		
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	
	function update_user($user_id, $item) {
		$this->db->where('user_id', $user_id);
		$query = $this->db->update('user', $item);
	
		if ($query == TRUE) {
			$data['is_true'] = TRUE;
			$data['message'] = 'Successfully updated user.';
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Failed to update usesr.';
			return $data;
		}
	}	
	
	function delete_user($user_id) {
		$this->db->where('user_id', $user_id);
		$query = $this->db->delete('user');
		
		if ($query) {
			$data['is_true'] = TRUE;
			$data['message'] = 'Successfully deleted user!';
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Failed to delete user!';
			return $data;
		}
	}
	
	function get_username($email) {
		$this->db->select('username');
		$this->db->where('email', $email);
		
		$query = $this->db->get('user');
		
		if ($query->num_rows() > 0) {
			$data['message'] = 'Successfully retrieved email!';
			$data['is_true'] = TRUE;
			$data['query_result'] = $query->result();
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Failed to find any matching usernames!';
			return $data;
		}
	}
	
	function verify_recover_password($username, $email) {
		$this->db->select('user_id');
		$this->db->where('username', $username);
		$this->db->where('email', $email);
		
		$query = $this->db->get('user');
		
		if ($query->num_rows() == 1) {
			$data['query_result'] = $query->result();
			$data['message'] = 'The username and email match';
			$data['is_true'] = TRUE;
			return $data;
		} elseif ($query->num_rows() > 1) {
			$data['message'] = 'An error has occurred. Please contact a site admin';
			//$data['reference_code'] = '008';
			//$data['dev_message'] = 'User exists in the database twice (username and email)';
			$data['is_true'] = FALSE;
			return $data;
		} else {
			$data['is_true'] = FALSE;
			$data['message'] = 'Failed to find any matching data!';
			return $data;
		}
	}
	
}