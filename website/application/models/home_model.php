<?php
class Home_Model extends CI_Model
{
	public function __construct()
  	{
    	$this->load->database();
  	}

  	public function get_book_need($major,$grade)
  	{

		$sql = "SELECT `id`,`name`,`course_name`,`author`,`course_category`,`publish`,`version` FROM `allbook` WHERE major=? AND grade=?";
		$query = $this->db->query($sql,array($major,$grade));
		return $query->result_array();  		
  	}
/**
 * 系统匹配书源�?
 *
 * @todo   get系统匹配书源�?
 * @access public
 * @param  $match
 * @return array
 */
  	public function get_system_match(array $match)
  	{
  		$book = array();
  		$user = array();
      $uid = $this->session->userdata['uid'];
      if($uid==NULL)
      {
        $uid = 0;
      }
  		$i = 0;
  		$sql = "SELECT `from_id`,`book_id` FROM `circulating_book` WHERE book_status=1 AND `from_id`!=$uid";
  		$query = $this->db->query($sql);
  		$result = $query->result_array();
  		foreach ($match as $value) 
  		{  			
	  		foreach ($result as $row) 
	  		{
	  			if($value['id']==$row['book_id'])
	  			{
	  				$book[$i] = $row;
	  				$book[$i]['book_id'] = $value['id'];
		            $book[$i]['name'] = $value['name'];
	  				$i++;
	  			}
	  		}  		
		}
		$sql2 = "SELECT `id`,`truename`,`dormitory`,`major` FROM `user` WHERE `id`!=$uid"; 
		$query2 = $this->db->query($sql2);
		$result = $query2->result_array();
		$j = 1;	
		foreach ($result as $row) 
  		{  		
  			$j = 1;	
	  		foreach ($book as $value)
	  		{
	  			if($value['from_id']==$row['id'])
	  			{
	  				$user[$row['id']] = $row;
	  				$user[$row['id']]['number'] = $j;
	  				$j++;	
	  			}
	  		}	  			
		}
		return array('user'=>$user,'book'=>$book);
  	}

  	public function get_userinfo($from_id)
  	{
  		$sql = "SELECT `id`,`truename`,`faculty`,`major`,`grade`,`dormitory`,`phone_number`,`subphone_number` FROM `user` WHERE `id`=?";
  		$query = $this->db->query($sql,array($from_id));
  		return $query->result_array();
  	}

  	public function get_userbook($user_id)
  	{
  		$sql = "SELECT `allbook`.`id`,`name`,`course_name`,`author`,`course_category`,`publish`,`version` FROM `circulating_book`,`allbook` WHERE `circulating_book`.`from_id`=? AND `allbook`.`id`=`circulating_book`.`book_id`";
  		$query = $this->db->query($sql,array($user_id));
  		return $query->result_array();
	}
	
	public function get_bookborrow($segs,$num)
    {
      $book = $this->get_userbook($segs[4]);
      $match = array();
      foreach ($book as $key => $value) 
      {
         for($i=6;$i<=$num;$i++)
         {
            if($value['id']==$segs[$i])
            {
                $match[$key] = $value;
            }          
         }
      }
      return $match;     
    }

    public function update_info(array $info)
    {
      $sql = "UPDATE `circulating_book` SET `to_id`=?,`circulate_number`=`circulate_number`+1,`book_right`=1,`change_time`=NOW() 
            WHERE `book_id`=?";
      $n = (count($info['book'])-1)*10;
      foreach($info['book'] as $key=>$value)
      {
        if($key!='from_id')
        {
          $query = $this->db->query($sql,array($info['to_id'],$value));
        }
      }
      $this->db->query("UPDATE `user` SET `points`=`points`-$n WHERE `id`=".$info['to_id']);
      $this->db->query("UPDATE `user` SET `points`=`points`+$n WHERE `id`=".$info['from_id']);
    } 
}
