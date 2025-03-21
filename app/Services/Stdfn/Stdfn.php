<?php

namespace App\Services\Stdfn;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use App\Models\ExerciceComptable;
use App\Models\NotificationMail;
use App\Mail\MailDemandeATraiter;
use DB;
use Auth;
use Carbon\Carbon;

class Stdfn
{
	
	protected $author;
	protected const APP_STATUT_MAINTENANCE = 1;
	
	
	//indique si le site est ouvert 1 ou pas 0
	public static function getAppstatut(){
		return Self::APP_STATUT_MAINTENANCE;
	}
	
	
	public static function guidv4($data = null) {
		
		
		// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
		// $data = $data ?? random_bytes(16);
		// assert(strlen($data) == 16);
		
		//personalisée
		$data = (strlen($data) == 16 )? $data : random_bytes(16) ;
		
		
		// Set version to 0100
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		// Set bits 6-7 to 10
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

		// Output the 36 character UUID.
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
		
		
	}
	
	public static function SupprimerAccents($str){
    	$str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
		$str = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
		$str = preg_replace('#&[^;]+;#', '', $str);

		$str = strtr($str, 'ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
		$str = strtr($str, 'áàâäãåçéèêëíìîïñóòôöõúùûüýÿ', 'aaaaaaceeeeiiiinooooouuuuyy');
		$str = strtr($str, '[]|/', '----');
		$str = str_replace('(','',$str);
		$str = str_replace(')','',$str);

		return $str;
    }
    
	public static function clean_url($str){
    	$str = Self::SupprimerAccents($str);

    	$str = str_replace(' ','-', $str);

		return strtolower($str);
    }
    
	//Renvoi une chaine sur un nombre de caractère défini	
	public static function truncate($text, $n){
		
		$strlen = strlen($text);
		
		if ($strlen == $n) {
			
			$text = $text;
			
		}elseif($strlen > $n){
		
			$text = substr($text,0,$n);
		
		}elseif($strlen < $n){
			
			$diff = $n - $strlen;
		
			for($i = 0; $i < $diff; $i++){
				
				$text.=' ';
			
			}
			
		}
		
		return $text;
		
	}

	//pour les nombre a précéder de x zéro (0000...)
	public static function truncateN($text, $n){
		
		$strlen = strlen($text);
		
		if ($strlen == $n) {
			
			$text = $text;
			
		}elseif($strlen > $n){
		
			$text = substr($text,0,$n);
		
		}elseif($strlen < $n){
			
			$diff = $n - $strlen;
			$zero = '';
			
			for($i = 0; $i < $diff; $i++){
				
				$zero.='0';
			
			}
			
			$text = $zero.$text;
			
		}
		
		return $text;
		
	}

	
	
	
	public static function debug($chaine){
		
		print '<pre>';print_r($chaine);print '</pre>';
		
	}
					
	public static function random_color_part() {
		return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	public static function RandomColor() {
		return '#'.Stdfn::random_color_part() . Stdfn::random_color_part() . Stdfn::random_color_part();
	}
	
			
	public static function generer_id(){
		
		srand((double)microtime()*1000000); 
		$id ="ID-".strtoupper(substr(md5(uniqid(rand())),0,7)); 

		return $id;
		
	}
	
	public static function genererOTP(){
		
		srand((double)microtime()*1000000); 
		$id = strtoupper(substr(uniqid(rand()),0,4)); 

		return $id;
		
	}
	
	public static function SendMail(){

		// Récupération des paramètres du mail du client
        $email_data = array(
            'email' => $request->email,
            'nom' => $request->email,
            'prenoms' => $request->email,
            'otp' => $request->email,
        );
        // Envoie des paramètres du mail au client
        Mail::send('emails.email_otp', $email_data, function ($message) use ($email_data){
			$message->to($email_data['email'])
				->subject("Code de connexion")
				->from('noreply@anpe.com', 'ANPE');
        });

	}	
		
	//fn pour convertir les dates
	public static function dateToDB($date){
		$date = str_replace('-','/',$date);
		sscanf($date, "%2s/%2s/%4s", $jj, $mm, $aaaa);
		$dbdate= !empty($aaaa) ?$aaaa.'-'.$mm.'-'.$jj : null;
		
		return $dbdate;
	}
	
	public static function dateFromDB($date){
		$date = str_replace('/','-',$date);
		sscanf($date, "%4s-%2s-%2s", $aaaa, $mm, $jj);
		$outdate=!empty($aaaa) ? $jj.'/'.$mm.'/'.$aaaa : null;
		return $outdate;
	}
	
	public static function dateTimeFromDB($date){
		$date = str_replace('/','-',$date);
		sscanf($date, "%4s-%2s-%2s %2s:%2s:%2s", $aaaa, $mm, $jj,$hh,$ii,$ss);
		$outdate=!empty($aaaa) ? $jj.'/'.$mm.'/'.$aaaa.' à '.$hh.':'.$ii : null;
		return $outdate;
	}
	
	public static function timeFromDB($date){
		$date = str_replace('/','-',$date);
		sscanf($date, "%4s-%2s-%2s %2s:%2s:%2s", $aaaa, $mm, $jj,$hh,$ii,$ss);
		$outdate=!empty($hh) ? $hh.':'.$ii : null;
		return $outdate;
	}
	
	public static function date($date){
		$date = str_replace('/','-',$date);
		sscanf($date, "%4s-%2s-%2s %2s:%2s:%2s", $aaaa, $mm, $jj,$hh,$ii,$ss);
		$outdate=!empty($aaaa) ? $aaaa.'-'.$mm.'-'.$jj : null;
		
		
		return $outdate;
	}
	
	public static function isActionAutorisee($user_id, $action_code){
		$reponse = DB::select('SELECT * FROM action INNER JOIN action_autorisee USING(action_id) WHERE user_id = "'.$user_id.'" AND action_code="'.$action_code.'" AND action_autorisee_statut = "VALIDE"');
		
		if(!empty($reponse)){
			return true;
		}else{
			return false;
		}
	}

	//Autorisation des portiers
	public static function isDBActionAutorisee($user_id, $db_action_code){
		$reponse = DB::select('SELECT * FROM db_action INNER JOIN db_action_autorisee USING(db_action_id) WHERE user_id = "'.$user_id.'" AND db_action_code="'.$db_action_code.'" AND db_action_autorisee_statut = "VALIDE"');
		
		if(!empty($reponse)){
			return true;
		}else{
			return false;
		}
	}

	public static function genererCodeTicket($site_sbc, $type_action_code)
    {
        // Obtenir la date actuelle
        $date = Carbon::now();

        // Format : AAAAMMJJSS (Année, Mois, Jour, Secondes)
        $date_code = $date->format('YmdHis');

        // Générer le code du ticket
        $code_ticket = "{$site_sbc}-{$type_action_code}{$date_code}";

        return $code_ticket;
    }
	
}