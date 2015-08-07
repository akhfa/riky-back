<?php
class PagesController extends CI_Controller {

		private $app_location = "/var/www/html/kakatu/dist/Classification.jar";

        public function view()
        {

	        /* data untuk semua */
	        /* base url */
	        $url = "localhost";
	        /* mysql port */
			$port = "3306";
			/* database name */
			$db_name = "kakatupedia";
			/* database username */
			$db_username = "kakatu";
			/* database password */
			$db_pass = "kakatu";

			/* query untuk menyimpan data artikel */

	        /* data untuk build */
			/* category_query */
			$query1 = "SELECT pesan,judul,kategori FROM (klasifikasi_feedback NATURAL JOIN feedback) JOIN dict_category WHERE klasifikasi_feedback.klasifikasi = dict_category.id";
			/* level_query */
			$query2 = "SELECT pesan,judul,dict_level.level as tingkatan FROM (klasifikasi_feedback NATURAL JOIN feedback) JOIN dict_level WHERE klasifikasi_feedback.level = dict_level.id";
			/* article_query */
			$query3 = "SELECT konten,judul,dict_level.level as tingkatan FROM klasifikasi_artikel JOIN artikel JOIN dict_level WHERE klasifikasi_artikel.level = dict_level.id AND artikel.id = klasifikasi_artikel.id_artikel";
			/* model location, folder 'build' harus punya akses read-write */
			$model_location = "/var/www/html/kakatu/dist/model";


			/* data untuk cari kategori/level */
			/* question id */
	        /* title */
	        $title = "Kajian KPK, Tukar Guling Miratel Berpotensi Rugikan Telkom";
	        /* body */
	        $body = "JAKARTA, KOMPAS.com - Pimpinan sementara Komisi Pemberantasan Korupsi Johan Budi mengatakan, KPk telah lama melakukan kajian terkait perihal tukar guling saham atau share swap anak usaha PT Telekomunikasi Indonesia (Telkom) Tbk, PT Dayamitra Telekomunikasi (Mitratel), dengan PT Tower Bersama Infrastructure Tbk (TBIG). Hasil kajian tersebut menunjukkan potensi kerugian bagi Telkom. KPK dalam melakukan kajian, itu merugikan Telkom karena ada potensi kerugian, ujar Johan di Gedung KPK, Jakarta, Kamis (2/7/2015). Johan mengatakan, KPK telah mengimbau Telkom dan Kementerian Badan Usaha Milik Negara untuk mengurungkan rencana tukar guling tersebut. KPK, kata Johan, juga telah mengingatkan potensi kerugiannya. Kalau itu tetap dilaksanakan dan kalau di kemudian hari ada dugaan penyelewengan, KPK bisa masuk, kata Johan. Opsi share swap saham Mitratel dengan TBIG dianggap sebagai jalan terbaik bagi Telkom untuk membesarkan bisnis menara. Melalui opsi ini, Telkom tidak akan terus dibebani biaya modal untuk menambah jumlah menara yang nilainya bisa mencapai Rp 1,5 triliun – Rp 2 triliun per tahun. Selain itu, Telkom memiliki kesempatan untuk menjadi pemegang saham mayoritas di TBIG, perusahaan menara independen terbesar di Indonesia. Berdasarkan Conditional Share Exchange Agreement (CSEA) dengan TBIG, monetisasi Mitratel dilakukan dalam 4 bagian. Pertama, TBIG akan membeli 100 persen saham Telkom di Mitratel dengan kepemilikan 13,7 persen saham di TBIG. Kedua, Telkom akan mendapatkan tambahan dana senilai Rp 1,74 triliun setelah Mitratel bergabung dan mencapai target tertentu yang telah ditetapkan. Ketiga, TBIG akan mengambil alih utang Telkom sebesar Rp 2,63 triliun. Keempat, setelah transaksi ini tuntas, Telkom akan memperoleh dana Rp 543 miliar, untuk modal kerja atau tambahan aset setelah tanggal penilaian. Dengan skema transaksi itu maka Telkom akan mendapatkan nilai moneter sebesar Rp 4,9 triliun plus kepemilikan 13,7 persen saham di TBIG. Jika dikalkulasikan, nilai total 100 persen saham Mitratel melalui skema share swap dihargai sekitar Rp 11,25 triliun.";
	        /* categories list - id */
	        $categories = "1,10,12,14,3,4,5,6,7,9";
	        /* levels list - id */
	        $levels = "1,2";
	        /* query untuk menyimpan feedback */
	        $query_1 = "INSERT INTO feedback (id_feedback,judul,pesan,wkt,color,date_update,isClassified) VALUES ('a',?)";
	        /* query untuk menyimpan hasil klasifikasi feedback */
	        $query_2 = "INSERT INTO klasifikasi_feedback (id_feedback,klasifikasi,level) VALUES ('a',?)";
			/* query untuk menyimpan data artikel */
	        $query_artikel_1 = "INSERT INTO artikel (id,judul,konten) VALUES ('a',?)";
	        /* query untuk menyimpan hasil klasifikasi level artikel */
	        $query_artikel_2 = "INSERT INTO klasifikasi_artikel (id_artikel,kategori,level) VALUES ('a','10',?)";

	        /* data untuk batch */
	        /* query untuk fetch data dari DB */
	        $query = "SELECT id_feedback,pesan,judul FROM feedback WHERE isClassified = 0";
	        /* query digunakan untuk update, "?" adalah id, diurus oleh java */
	        $update_query = "UPDATE feedback SET isClassified = 1 WHERE id_feedback IN ?";
	        /* query digunakan untuk insert, "?" adalah id, diurus oleh java */
	        $insert_query = "INSERT INTO klasifikasi_feedback (id_feedback, klasifikasi, level) VALUES ?";

	        /* bacth classification */
	        // if($param == 1)
				echo $result = $this->batch($url, $port, $db_name, $db_username, $db_pass, $model_location, $query, $categories, $levels, $update_query, $insert_query);

	        /* build model -sekali saja- */
	   //      else if($param == 2)
				// echo $this->build($url, $port, $db_name, $db_username, $db_pass, $query1, $query2, $query3, $model_location);

	   //      /* klasifikasi kategori */
	   //      else if($param == 3)
				// echo $this->feedback($url, $port, $db_name, $db_username, $db_pass, $model_location, $title, $body, $categories, $levels, $query_1, $query_2);
	   //      /* klasifikasi level artikel */
	   //      else if($param == 4)
	   //      	echo $artikel = $this->article($url, $port, $db_name, $db_username, $db_pass, $model_location, $title, $body, $levels, $query_artikel_1, $query_artikel_2);
        }

        public function build($url, $port, $db_name, $db_username, $db_pass, $query1, $query2, $query3, $model_location)
        {
		ini_set('max_execution_time', 300); // set timeout to 300 sec (5 minutes)
		$command = "java -jar \"".$this->app_location."\" -build \"".$url."\" \"".$port."\" \"".$db_name."\" \"".$db_username."\" \"".$db_pass."\" \"".$query1."\" \"".$query2."\" \"".$query3."\" \"".$model_location."\"";

		return shell_exec($command);
        }

        public function feedback($url, $port, $db_name, $db_username, $db_pass, $model_location, $title, $body, $categories, $levels, $query_1, $query_2)
        {
	        $command = "java -jar \"".$this->app_location."\" -feedback \"".$url."\" \"".$port."\" \"".$db_name."\" \"".$db_username."\" \"".$db_pass."\" \"".$model_location."\" \"".$title."\" \"".$body."\" \"".$categories."\" \"".$levels."\" \"".$query_1."\" \"".$query_2."\"";
	        return shell_exec($command);
        }

        public function article($url, $port, $db_name, $db_username, $db_pass, $model_location, $title, $body, $levels, $query_artikel_1, $query_artikel_2)
        {
    		$command = "java -jar \"".$this->app_location."\" -article \"".$url."\" \"".$port."\" \"".$db_name."\" \"".$db_username."\" \"".$db_pass."\" \"".$model_location."\" \"".$title."\" \"".$body."\" \"".$levels."\" \"".$query_artikel_1."\" \"".$query_artikel_2."\"";
	        return shell_exec($command);
        }

        public function batch($url, $port, $db_name, $db_username, $db_pass, $model_location, $query, $categories, $levels, $update_query, $insert_query)
        {
        	$command = "java -jar \"".$this->app_location."\" -batch \"".$url."\" \"".$port."\" \"".$db_name."\" \"".$db_username."\" \"".$db_pass."\" \"".$model_location."\" \"".$query."\" \"".$categories."\" \"".$levels."\" \"".$update_query."\" \"".$insert_query."\"";
        	return shell_exec($command);
        }
}
