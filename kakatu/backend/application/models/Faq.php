<?php 
	class Faq extends CI_Model {

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		function get_faq_item($id_feedback){
			$query = $this->db->get_where('feedback', array('id_feedback' => $id_feedback));
			foreach($query->result() as $row)
				$result = $row;
			return $result;
		}

		function get_faq_by_level($kategori, $level){
			$this->db->select('feedback.id_feedback as id, feedback.judul as judul, dict_category.kategori as kategori')->from('feedback')->join('klasifikasi_feedback', 'feedback.id_feedback = klasifikasi_feedback.id_feedback');
			$this->db->join('dict_category', 'klasifikasi_feedback.klasifikasi = dict_category.id');
			$this->db->join('dict_level', 'klasifikasi_feedback.level = dict_level.id');
			$this->db->where(array('dict_category.kategori' => $kategori, 'dict_level.level' => $level));
			$this->db->order_by('wkt', 'DESC')->order_by('count', 'DESC');
			$query = $this->db->get();
			return $query->result();
		}

		//alternatif (belom)
		// function get_recommendation_faq($categories){
		// 	$this->db->select('feedback.id_feedback as id, feedback.judul as judul, dict_category.kategori as kategori')->from('feedback')->join('klasifikasi_feedback', 'feedback.id_feedback = klasifikasi_feedback.id_feedback');
		// 	$this->db->join('dict_category', 'klasifikasi_feedback.klasifikasi = dict_category.id');
		// 	$this->db->join('dict_level', 'klasifikasi_feedback.level = dict_level.id');
		// }
		
		function get_feedback_detail($id_feedback){
			$query = $this->db->order_by('date_created', 'DESC')->get_where('feedback_detail', array('id_feedback' => $id_feedback));
			return $query->result();
		}

		function get_kategori($id_feedback){
			$query = $this->db->select('klasifikasi')->get_where('klasifikasi_feedback', array('id_feedback' => $id_feedback));
			foreach($query->result() as $row)
				$result = $row->klasifikasi;
			if(isset($result)){
				$query2 = $this->db->select('kategori')->get_where('dict_category', array('id' => $result));
				foreach($query2->result() as $row)
					$result2 = $row->kategori;
			}
			else
				$result2 = '';
			return $result2;
		}

		function search($keyword, $limit, $page){
			$offset = ($page - 1) * $limit;
			$keys = explode(" ", $keyword);
			$result = array();
			
			$this->db->select('feedback.id_feedback as id_feedback, judul')->from('feedback')->join('feedback_detail', 'feedback.id_feedback = feedback_detail.id_feedback');
			foreach($keys as $key){
				$this->db->or_like('judul', $key)->or_like('pesan', $key);
				$this->db->or_like('komentar', $key);
			}
			$this->db->order_by('wkt', 'DESC')->order_by('count', 'DESC');
			$query = $this->db->limit($limit, $offset)->get();

			foreach($query->result() as $row){
					$result[] = array('id' => $row->id_feedback, 'judul' => $row->judul, 'kategori' => $this->get_kategori($row->id_feedback));
			}
			return $result;
		}

		// function is_already_inserted($val, $array){
		// 	$check = false;
		// 	foreach($array as $row){
		// 		if(in_array($val, $row)){
		// 			$check = true;
		// 			break;
		// 		}
		// 	}
		// 	return $check;
		// }

		function get_top_10(){
			$this->db->select('feedback.id_feedback as id, feedback.judul as judul')->from('feedback');
			$this->db->order_by('wkt', 'DESC')->order_by('count', 'DESC')->limit(10);
			$query = $this->db->get();
			$result = array();
			foreach($query->result() as $row)
				$result[] = array('id' => $row->id, 'judul' => $row->judul, 'kategori' => $this->get_kategori($row->id));
			return $result;
		}

		function add_count($id_feedback){
			$query = "UPDATE feedback SET count = count+1 WHERE id_feedback = '".$id_feedback."'";
			$res = $this->db->query($query);
			return $res;
		}

		function get_related_faq($id_feedback, $limit, $page){
			$offset = ($page - 1) * $limit;
			$kategori = $this->get_kategori($id_feedback);
			$tags = $this->get_tag_list($id_feedback);
			

			// $this->db->select('feedback.id_feedback as id, feedback.judul as judul, dict_category.kategori as kategori')->from('feedback')->join('tag_feedback', 'feedback.id_feedback = tag_feedback.id_feedback')->join('klasifikasi_feedback', 'feedback.id_feedback = klasifikasi_feedback.id_feedback');
			// $this->db->join('dict_category', 'klasifikasi_feedback.klasifikasi = dict_category.id');
			// $this->db->where('dict_category.kategori', $kategori);
			// if($tags != null){
			// 	$this->db->group_start();
			// 	foreach($tags as $row)
			// 		$this->db->or_where('tag', $row);
			// 	$this->db->group_end();
			// }
			// // $this->db->order_by('wkt', 'DESC')->order_by('count', 'DESC');
			// $query1 = $this->db->get_compiled_select();

			// // $this->db->reset_query();

			// $this->db->select('feedback.id_feedback as id, feedback.judul as judul, dict_category.kategori as kategori')->from('feedback')->join('tag_feedback', 'feedback.id_feedback = tag_feedback.id_feedback')->join('klasifikasi_feedback', 'feedback.id_feedback = klasifikasi_feedback.id_feedback');
			// $this->db->join('dict_category', 'klasifikasi_feedback.klasifikasi = dict_category.id');
			// $this->db->where('dict_category.kategori !=', $kategori);
			// if($tags != null){
			// 	$this->db->group_start();
			// 	foreach($tags as $row)
			// 		$this->db->or_where('tag', $row);
			// 	$this->db->group_end();
			// }
			// $this->db->order_by('wkt', 'DESC');
			// $this->db->order_by('count', 'DESC');
			// $query2 = $this->db->get_compiled_select();

			// $query = $this->db->from("($query1 UNION $query2) as related_feedback")->limit($limit, $offset)->get();

			// return $query->result();

			$query = 'SELECT id, judul, kategori FROM (SELECT `feedback`.`id_feedback` as `id`, `feedback`.`judul` as `judul`, `dict_category`.`kategori` as `kategori`, `wkt`, `count` 
				FROM `feedback` JOIN `tag_feedback` ON `feedback`.`id_feedback` = `tag_feedback`.`id_feedback` JOIN `klasifikasi_feedback` 
				ON `feedback`.`id_feedback` = `klasifikasi_feedback`.`id_feedback` JOIN `dict_category` ON `klasifikasi_feedback`.`klasifikasi` = `dict_category`.`id` 
				WHERE `dict_category`.`kategori` = \'';
			$query = $query . $kategori . '\'';
			if($tags != null){
				$cc = count($tags);
				if($cc > 1){
					$query = $query . ' AND (tag = \'' . $tags[0] . '\'';
					$i = 1;
					do{
						$query = $query . ' OR tag = \'' . $tags[$i] . '\'';
						$i++;
					} while($i < $cc);
					$query = $query . ')';
				}
				else{
					$query = $query . ' AND tag = \'' . $tags[0] . '\'';
				}
			}
			$query = $query . ' UNION SELECT `feedback`.`id_feedback` as `id`, `feedback`.`judul` as `judul`, `dict_category`.`kategori` as `kategori`, `wkt`, `count` 
			FROM `feedback` JOIN `tag_feedback` ON `feedback`.`id_feedback` = `tag_feedback`.`id_feedback` JOIN `klasifikasi_feedback` ON `feedback`.`id_feedback` = `klasifikasi_feedback`.`id_feedback` 
			JOIN `dict_category` ON `klasifikasi_feedback`.`klasifikasi` = `dict_category`.`id` WHERE `dict_category`.`kategori` != \'';
			$query = $query . $kategori . '\'';
			if($tags != null){
				$cc = count($tags);
				if($cc > 1){
					$query = $query . ' AND (tag = \'' . $tags[0] . '\'';
					$i = 1;
					do{
						$query = $query . ' OR tag = \'' . $tags[$i] . '\'';
						$i++;
					} while($i < $cc);
					$query = $query . ')';
				}
				else{
					$query = $query . ' AND tag = \'' . $tags[0] . '\'';
				}
			}

			$query = $query . ' ORDER BY `wkt` DESC, `count` DESC) as related_feedback LIMIT 5';

			return $this->db->query($query)->result();
		}

		/******************/
		/*  MISCELLANEOUS */
		/******************/

		function add_faq($data){
			return $this->db->insert('feedback', $data);
		}

		function add_feedback_detail($data){
			return $this->db->insert('feedback_detail', $data);
		}

		function add_tag($id_feedback, $tag){
			if(!$this->Tag->is_tag_exist($tag))
				$this->Tag->add_tag($tag);
			if($this->is_tag_already_added($id_feedback, $tag)){
				$object = array('id_feedback' => $id_feedback, 'tag' => $tag);
				return $this->insert('tag_feedback', $object);
			}
			else
				return true;
		}

		function get_tag_list($id_feedback){
			$query = $this->db->select('tag')->get_where('tag_feedback', array('id_feedback' => $id_feedback));
			$result = array();
			foreach($query->result() as $row)
				$result[] = $row->tag;
			return $result;
		}

		function is_tag_already_added($id_feedback, $tag){
			$query = $this->db->get_where('tag_feedback', array('id_feedback' => $id_feedback, 'tag' => $tag));
			foreach($query->result() as $row)
				$result = $row;
			if(isset($result))
				return true;
			else
				return false;
		}
	}
?>