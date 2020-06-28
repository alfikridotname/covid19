<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		$this->sync('indonesia');
		$this->sync('kasus_indonesia');
		$this->sync('global');
		$this->sync('kasus_global');
	}

	public function sync($kategori)
	{
		$output = [
			'status' => false,
			'data' 	 => []
		];

		switch ($kategori) {
			case 'indonesia':
				$link = "https://api.kawalcorona.com/indonesia/provinsi";
				break;
			case 'kasus_indonesia':
				$link = "https://api.kawalcorona.com/indonesia/provinsi";
				break;
			case 'global':
				$link = "https://api.kawalcorona.com/";
				break;
			case 'kasus_global':
				$link = "https://api.kawalcorona.com/";
				break;
		}

		$data 		= file_get_contents($link);
		$konversi   = json_decode($data, true);

		foreach ($konversi as $key => $value) {
			if ($kategori == 'indonesia') :
				$table 		= "indonesia";
				$primary 	= [
					'id_provinsi' 	=> trim($value['attributes']['Kode_Provi'])
				];
				$insert 	= [
					'id_provinsi' 	=> trim($value['attributes']['Kode_Provi']),
					'provinsi' 		=> trim($value['attributes']['Provinsi']),
					'updated_at'	=> time()
				];
				$update 	= [
					'provinsi' 		=> trim($value['attributes']['Provinsi']),
					'updated_at'	=> time()
				];
			elseif ($kategori == 'kasus_indonesia') :
				$table 		= "kasus_indonesia";
				$primary 	= [
					'id_provinsi' 	=> trim($value['attributes']['Kode_Provi'])
				];
				$insert 	= [
					'id_provinsi' 	=> trim($value['attributes']['Kode_Provi']),
					'positif' 		=> trim($value['attributes']['Kasus_Posi']),
					'sembuh' 		=> trim($value['attributes']['Kasus_Semb']),
					'meninggal' 	=> trim($value['attributes']['Kasus_Meni']),
					'updated_at'	=> time()
				];
				$update 	= [
					'positif' 		=> trim($value['attributes']['Kasus_Posi']),
					'sembuh' 		=> trim($value['attributes']['Kasus_Semb']),
					'meninggal' 	=> trim($value['attributes']['Kasus_Meni']),
					'updated_at'	=> time()
				];
			elseif ($kategori == 'global') :
				$table 		= "global";
				$primary 	= [
					'id_negara' 	=> trim($value['attributes']['OBJECTID'])
				];
				$insert 	= [
					'id_negara' 	=> trim($value['attributes']['OBJECTID']),
					'negara' 		=> trim($value['attributes']['Country_Region']),
					'updated_at'	=> time()
				];
				$update 	= [
					'negara' 		=> trim($value['attributes']['Country_Region']),
					'updated_at'	=> time()
				];
			elseif ($kategori == 'kasus_global') :
				$table 		= "kasus_global";
				$primary 	= [
					'id_negara' 	=> trim($value['attributes']['OBJECTID'])
				];
				$insert 	= [
					'id_negara' 	=> trim($value['attributes']['OBJECTID']),
					'lat' 			=> trim($value['attributes']['Lat']),
					'lng' 			=> trim($value['attributes']['Long_']),
					'konfirmasi' 	=> trim($value['attributes']['Confirmed']),
					'meninggal' 	=> trim($value['attributes']['Deaths']),
					'sembuh' 		=> trim($value['attributes']['Recovered']),
					'positif' 		=> trim($value['attributes']['Active']),
					'updated_at' 	=> trim($value['attributes']['Last_Update'])
				];
				$update 	= [
					'lat' 			=> trim($value['attributes']['Lat']),
					'lng' 			=> trim($value['attributes']['Long_']),
					'konfirmasi' 	=> trim($value['attributes']['Confirmed']),
					'meninggal' 	=> trim($value['attributes']['Deaths']),
					'sembuh' 		=> trim($value['attributes']['Recovered']),
					'positif' 		=> trim($value['attributes']['Active']),
					'updated_at' 	=> trim($value['attributes']['Last_Update'])
				];
			endif;

			$cek = $this->db->get_where($table, $primary)->num_rows();
			if ($cek > 0) {
				$this->db->update($table, $update, $primary);
			} else {
				$this->db->insert($table, $insert);
			}
		}
	}
}
