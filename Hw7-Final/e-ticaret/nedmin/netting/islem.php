<?php
ob_start();
//session_start();

include 'baglan.php';
include '../production/fonksiyon.php'; // bu önemli dahil et
//if (isset($_POST['genelayarkaydet'])) {
  //echo "Hoş beş geldin";}


  if (isset($_POST['kullanicikaydet'])) {


  	echo $kullanici_adsoyad=htmlspecialchars($_POST['kullanici_adsoyad']);  echo "<br>";
  	echo $kullanici_mail=htmlspecialchars($_POST['kullanici_mail']); echo "<br>";

  	echo $kullanici_passwordone=$_POST['kullanici_passwordone']; echo "<br>";
  	echo $kullanici_passwordtwo=$_POST['kullanici_passwordtwo']; echo "<br>";

if ($kullanici_passwordone==$kullanici_passwordtwo) {

  if (strlen($kullanici_passwordone)>=6) {
    // Başlangıç

			$kullanicisor=$db->prepare(" SELECT * from kullanici where kullanici_mail=:mail");
			$kullanicisor->execute(array(
				'mail' => $kullanici_mail
				));

			//dönen satır sayısını belirtir
			$say=$kullanicisor->rowCount();



			if ($say==0) {

				//md5 fonksiyonu şifreyi md5 şifreli hale getirir.
				$password=md5($kullanici_passwordone);

				$kullanici_yetki=1;


			//Kullanıcı kayıt işlemi yapılıyor...
				$kullanicikaydet=$db->prepare("INSERT INTO kullanici SET
					kullanici_adsoyad=:kullanici_adsoyad,
					kullanici_mail=:kullanici_mail,
					kullanici_password=:kullanici_password,
					kullanici_yetki=:kullanici_yetki
					");
				$insert=$kullanicikaydet->execute(array(
					'kullanici_adsoyad' => $kullanici_adsoyad,
					'kullanici_mail' => $kullanici_mail,
					'kullanici_password' => $password,
					'kullanici_yetki' => $kullanici_yetki
					));

				if ($insert) {
          //echo "Kayıt Başarılı";
          header("Location:../../index.php?durum=loginbasarili");



					//Header("Location:../production/genel-ayarlar.php?durum=ok");

				} else {


					header("Location:../../register.php?durum=basarisiz");
				}

			} else {

				header("Location:../../register.php?durum=mukerrerkayit");



			}




		// Bitiş



  }else{

    header("Location:../../register.php?durum=eksiksifre");
  }



}else{
  header("Location:../../register.php?durum=farklisifre");

}
}




if (isset($_POST['kullanicibilgiguncelle'])) {

	$kullanici_id=$_POST['kullanici_id'];

	$ayarkaydet=$db->prepare("UPDATE kullanici SET
		kullanici_adsoyad=:kullanici_adsoyad,
		kullanici_il=:kullanici_il,
		kullanici_ilce=:kullanici_ilce
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update=$ayarkaydet->execute(array(
		'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
		'kullanici_il' => $_POST['kullanici_il'],
		'kullanici_ilce' => $_POST['kullanici_ilce']
		));


	if ($update) {

		Header("Location:../../hesabim?durum=ok");

	} else {

		Header("Location:../../hesabim?durum=no");
	}

}


if ($_GET['kullanicisil']=="ok") {

	$sil=$db->prepare("DELETE from kullanici where kullanici_id=:id");
	$kontrol=$sil->execute(array(
		'id' => $_GET['kullanici_id']
		));


	if ($kontrol) {


		header("location:../production/kullanici.php?sil=ok");


	} else {

		header("location:../production/kullanici.php?sil=no");

	}


}




  if (isset($_POST['sliderkaydet'])) {


  	$uploads_dir = '../../dmg/slider';
  	@$tmp_name = $_FILES['slider_resimyol']["tmp_name"];
  	@$name = $_FILES['slider_resimyol']["name"];
  	//resmin isminin benzersiz olması
  	$benzersizsayi1=rand(20000,32000);
  	$benzersizsayi2=rand(20000,32000);
  	$benzersizsayi3=rand(20000,32000);
  	$benzersizsayi4=rand(20000,32000);
  	$benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;

  	$refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
  	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");



  	$kaydet=$db->prepare("INSERT INTO slider SET
  		slider_ad=:slider_ad,
  		slider_sira=:slider_sira,
  		slider_link=:slider_link,
  		slider_resimyol=:slider_resimyol
  		");
  	$insert=$kaydet->execute(array(
  		'slider_ad' => $_POST['slider_ad'],
  		'slider_sira' => $_POST['slider_sira'],
  		'slider_link' => $_POST['slider_link'],
  		'slider_resimyol' => $refimgyol
  		));

  	if ($insert) {

  		Header("Location:../production/slider.php?durum=ok");

  	} else {

  		Header("Location:../production/slider.php?durum=no");
  	}




  }


  if (@$_GET['slidersil']=="ok") {

	$sil=$db->prepare("DELETE from slider where slider_id=:slider_id");
	$kontrol=$sil->execute(array(
		'slider_id' => $_GET['slider_id']
		));

	if ($kontrol) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../production/slider.php?durum=ok");

	} else {

		Header("Location:../production/slider.php?durum=no");
	}

}









  if (isset($_POST['logoduzenle'])) {



  	$uploads_dir = '../../dmg';

  	@$tmp_name = $_FILES['ayar_logo']["tmp_name"];
  	@$name = $_FILES['ayar_logo']["name"];

  	$benzersizsayi4=rand(20000,32000);
    $refimgyol=substr($uploads_dir, 6)."/".$benzersizsayi4.$name;

    //dmg/24321indir.png

  	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizsayi4$name");


  	$duzenle=$db->prepare("UPDATE setting SET
  		ayar_logo=:logo
  		WHERE ayar_id=0");
  	$update=$duzenle->execute(array(
  		'logo' => $refimgyol
  		));



  	if ($update) {

  		$resimsilunlink=$_POST['eski_yol'];
  		unlink("../../$resimsilunlink");//unlink dosya silmeye yarar.

  		Header("Location:../production/genel-ayar.php?durum=ok");

  	} else {

  		Header("Location:../production/genel-ayar.php?durum=no");
  	}

  }





if (isset($_POST['admingiris'])) {
  //echo ($_POST['kullanici_mail']);
	//echo ($_POST['kullanici_password']);
  $kullanici_mail=$_POST['kullanici_mail'];
  $kullanici_password=md5($_POST['kullanici_password']);
  $kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail and kullanici_password=:password and
    kullanici_yetki=:yetki");
	$kullanicisor->execute(array(
		'mail' => $kullanici_mail,
		'password' => $kullanici_password,
		'yetki' => 5
		));

	echo $say=$kullanicisor->rowCount();

	if ($say==1) {

		$_SESSION['kullanici_mail']=$kullanici_mail; //session değişkeni taracıyı kapatana kadar aktif olur
		header("Location:../production/index.php");//o kullanıcı ordamı değil mi
		exit;



	} else {

		header("Location:../production/login.php?durum=no");
		exit;
	}


}



if (isset($_POST['kullanicigiris'])) {
  //echo "Doğru yer";
  //exit;



	echo $kullanici_mail=htmlspecialchars($_POST['kullanici_mail']);
	echo $kullanici_password=md5($_POST['kullanici_password']);



	$kullanicisor=$db->prepare(" SELECT * FROM kullanici where kullanici_mail=:mail and kullanici_yetki=:yetki and
  kullanici_password=:password ");
	$kullanicisor->execute(array(
		'mail' => $kullanici_mail,
		'yetki' => 1,
		'password' => $kullanici_password,

		));


	$say=$kullanicisor->rowCount();



	if ($say==1) {


		echo $_SESSION['userkullanici_mail']=$kullanici_mail;

		header("Location:../../");
		exit;





} else {


		header("Location:../../?durum=basarisizgiris");

	}


}






if (isset($_POST['genelayarkaydet'])) {

	//Tablo güncelleme işlemii
  $ayarkaydet=$db->prepare("UPDATE setting SET
		ayar_title=:ayar_title,
		ayar_description=:ayar_description,
		ayar_keywords=:ayar_keywords,
		ayar_author=:ayar_author
		WHERE ayar_id=0");

	$update=$ayarkaydet->execute(array(
		'ayar_title' => $_POST['ayar_title'],
		'ayar_description' => $_POST['ayar_description'],
		'ayar_keywords' => $_POST['ayar_keywords'],
		'ayar_author' => $_POST['ayar_author']
		));


	if ($update) {//update sonucu boş dönmezse yap

		header("Location:../production/genel-ayar.php?durum=ok");

	} else {

		header("Location:../production/genel-ayar.php?durum=no");
	}

}



if (isset($_POST['iletisimayarkaydet'])) {
//  echo "oldu";}
//Tablo güncelleme işlemii
  $ayarkaydet=$db->prepare("UPDATE setting SET
    ayar_phone=:ayar_phone,
    ayar_gsm=:ayar_gsm,
    ayar_fax=:ayar_fax,
    ayar_mail=:ayar_mail,
    ayar_town=:ayar_town,
    ayar_country=:ayar_country,
    ayar_addrress=:ayar_addrress,
    ayar_shift=:ayar_shift
    WHERE ayar_id=0");

  $update=$ayarkaydet->execute(array(
    'ayar_phone' => $_POST['ayar_phone'],
    'ayar_gsm' => $_POST['ayar_gsm'],
    'ayar_fax' => $_POST['ayar_fax'],
    'ayar_mail' => $_POST['ayar_mail'],
    'ayar_town' => $_POST['ayar_town'],
    'ayar_country' => $_POST['ayar_country'],
    'ayar_address' => $_POST['ayar_address'],
    'ayar_shift' => $_POST['ayar_shift']
  ));


  if ($update) {

    header("Location:../production/iletisim-ayarlar.php?durum=ok");

} else {

    header("Location:../production/iletisim-ayarlar.php?durum=no");
}

}


if (isset($_POST['apiayarkaydet'])) {

	//Tablo güncelleme işlemi kodları...
	$ayarkaydet=$db->prepare("UPDATE setting SET

		ayar_analystic=:ayar_analystic,
		ayar_maps=:ayar_maps,
		ayar_zopim=:ayar_zopim
		WHERE ayar_id=0");

	$update=$ayarkaydet->execute(array(

		'ayar_analystic' => $_POST['ayar_analystic'],
		'ayar_maps' => $_POST['ayar_maps'],
		'ayar_zopim' => $_POST['ayar_zopim']
		));


	if ($update) {

		header("Location:../production/api-ayarlar.php?durum=ok");

	} else {

		header("Location:../production/api-ayarlar.php?durum=no");
	}

}
if (isset($_POST['sosyalayarkaydet'])) {

	$ayarkaydet=$db->prepare("UPDATE setting SET

		ayar_facebook=:ayar_facebook,
		ayar_twitter=:ayar_twitter,
		ayar_youtube=:ayar_youtube,
    ayar_google=:ayar_google

		WHERE ayar_id=0");

	$update=$ayarkaydet->execute(array(

		'ayar_facebook' => $_POST['ayar_facebook'],
		'ayar_twitter' => $_POST['ayar_twitter'],
		'ayar_youtube' => $_POST['ayar_youtube'],
    'ayar_google' => $_POST['ayar_google']
		));


	if ($update) {

		header("Location:../production/sosyal-ayarlar.php?durum=ok");

	} else {

		header("Location:../production/sosyal-ayarlar.php?durum=no");
	}

}


if (isset($_POST['mailayarkaydet'])) {

	$ayarkaydet=$db->prepare("UPDATE setting SET

		ayar_smtphost=:ayar_smtphost,
    ayar_smtpuser=:ayar_smtpuser,
		ayar_smtppassword=:ayar_smtppassword,
		ayar_smtpport=:ayar_smtpport

		WHERE ayar_id=0");

	$update=$ayarkaydet->execute(array(

		'ayar_smtphost' => $_POST['ayar_smtphost'],
		'ayar_smtpuser' => $_POST['ayar_smtpuser'],
		'ayar_smtppassword' => $_POST['ayar_smtppassword'],
    'ayar_smtpport' => $_POST['ayar_smtpport']
		));


	if ($update) {

		header("Location:../production/mail-ayarlar.php?durum=ok");

	} else {

		header("Location:../production/mail-ayarlar.php?durum=no");
	}

}


if (isset($_POST['hakkimizdakaydet'])) {


	$ayarkaydet=$db->prepare("UPDATE hakkimizda SET
		hakkimizda_baslik=:hakkimizda_baslik,
		hakkimizda_icerik=:hakkimizda_icerik,
		hakkimizda_video=:hakkimizda_video,
		hakkimizda_vizyon=:hakkimizda_vizyon,
		hakkimizda_misyon=:hakkimizda_misyon
		WHERE hakkimizda_id=0");

	$update=$ayarkaydet->execute(array(
		'hakkimizda_baslik' => $_POST['hakkimizda_baslik'],
		'hakkimizda_icerik' => $_POST['hakkimizda_icerik'],
		'hakkimizda_video' => $_POST['hakkimizda_video'],
		'hakkimizda_vizyon' => $_POST['hakkimizda_vizyon'],
		'hakkimizda_misyon' => $_POST['hakkimizda_misyon']
		));


	if ($update) {

		header("Location:../production/hakkimizda.php?durum=ok");

	} else {

		header("Location:../production/hakkimizda.php?durum=no");
	}

}



if (isset($_POST['kullaniciduzenle'])) {

	$kullanici_id=$_POST['kullanici_id'];

	$ayarkaydet=$db->prepare("UPDATE kullanici SET
		kullanici_tc=:kullanici_tc,
		kullanici_adsoyad=:kullanici_adsoyad,
		kullanici_durum=:kullanici_durum
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update=$ayarkaydet->execute(array(
		'kullanici_tc' => $_POST['kullanici_tc'],
		'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
		'kullanici_durum' => $_POST['kullanici_durum']
		));


	if ($update) {

		Header("Location:../production/kullanici-duzenle.php?kullanici_id=$kullanici_id&durum=ok");

	} else {

		Header("Location:../production/kullanici-duzenle.php?kullanici_id=$kullanici_id&durum=no");
	}

}


if (@$_GET['kullanicisil']=="ok") { // burdaki kod blogu çalışıyor Kullanıcılarda  sil dediğinde.

	$sil=$db->prepare("DELETE from kullanici where kullanici_id=:id");
	$kontrol=$sil->execute(array(
		'id' => $_GET['kullanici_id']
		));


	if ($kontrol) {


		header("location:../production/kullanici.php?sil=ok");


	} else {

		header("location:../production/kullanici.php?sil=no");

	}


}

if (isset($_POST['menuduzenle'])) {

	$menu_id=$_POST['menu_id'];

	$menu_seourl=seo($_POST['menu_ad']);


	$ayarkaydet=$db->prepare("UPDATE menu SET
		menu_ad=:menu_ad,
		menu_detay=:menu_detay,
		menu_url=:menu_url,
		menu_sira=:menu_sira,
		menu_seourl=:menu_seourl,
		menu_durum=:menu_durum
		WHERE menu_id={$_POST['menu_id']}");

	$update=$ayarkaydet->execute(array(
		'menu_ad' => $_POST['menu_ad'],
		'menu_detay' => $_POST['menu_detay'],
		'menu_url' => $_POST['menu_url'],
		'menu_sira' => $_POST['menu_sira'],
		'menu_seourl' => $menu_seourl,
		'menu_durum' => $_POST['menu_durum']
		));


	if ($update) {

		Header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=ok");

	} else {

		Header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=no");
	}

}


if (isset($_POST['menuduzenle'])) {

	$menu_id=$_POST['menu_id'];

	$menu_seourl=seo($_POST['menu_ad']);


	$ayarkaydet=$db->prepare("UPDATE menu SET
		menu_ad=:menu_ad,
		menu_detay=:menu_detay,
		menu_url=:menu_url,
		menu_sira=:menu_sira,
		menu_seourl=:menu_seourl,
		menu_durum=:menu_durum
		WHERE menu_id={$_POST['menu_id']}");

	$update=$ayarkaydet->execute(array(
		'menu_ad' => $_POST['menu_ad'],
		'menu_detay' => $_POST['menu_detay'],
		'menu_url' => $_POST['menu_url'],
		'menu_sira' => $_POST['menu_sira'],
		'menu_seourl' => $menu_seourl,
		'menu_durum' => $_POST['menu_durum']
		));


	if ($update) {

		Header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=ok");

	} else {

		Header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=no");
	}

}





if (@$_GET['menusil']=="ok") {

	$sil=$db->prepare("DELETE from menu where menu_id=:id");
	$kontrol=$sil->execute(array(
		'id' => $_GET['menu_id']
		));


	if ($kontrol) {


		header("location:../production/menu.php?sil=ok");


	} else {

		header("location:../production/menu.php?sil=no");

	}


}



if (isset($_POST['menukaydet'])) {


	$menu_seourl=seo($_POST['menu_ad']);


	$ayarekle=$db->prepare("INSERT INTO menu SET
		menu_ad=:menu_ad,
		menu_detay=:menu_detay,
		menu_url=:menu_url,
		menu_sira=:menu_sira,
		menu_seourl=:menu_seourl,
		menu_durum=:menu_durum
		");

	$insert=$ayarekle->execute(array(
		'menu_ad' => $_POST['menu_ad'],
		'menu_detay' => $_POST['menu_detay'],
		'menu_url' => $_POST['menu_url'],
		'menu_sira' => $_POST['menu_sira'],
		'menu_seourl' => $menu_seourl,
		'menu_durum' => $_POST['menu_durum']
		));


	if ($insert) {

		Header("Location:../production/menu.php?durum=ok");

	} else {

		Header("Location:../production/menu.php?durum=no");
	}

}



if (isset($_POST['kategoriduzenle'])) {

	$kategori_id=$_POST['kategori_id'];
	$kategori_seourl=seo($_POST['kategori_ad']);


	$kaydet=$db->prepare("UPDATE kategori SET
		kategori_ad=:ad,
		kategori_durum=:kategori_durum,
		kategori_seourl=:seourl,
		kategori_sira=:sira
		WHERE kategori_id={$_POST['kategori_id']}");
	$update=$kaydet->execute(array(
		'ad' => $_POST['kategori_ad'],
		'kategori_durum' => $_POST['kategori_durum'],
		'seourl' => $kategori_seourl,
		'sira' => $_POST['kategori_sira']
		));

	if ($update) {

		Header("Location:../production/kategori-duzenle.php?durum=ok&kategori_id=$kategori_id");

	} else {

		Header("Location:../production/kategori-duzenle.php?durum=no&kategori_id=$kategori_id");
	}

}



if (isset($_POST['kategoriekle'])) {

	$kategori_seourl=seo($_POST['kategori_ad']);

	$kaydet=$db->prepare("INSERT INTO kategori SET
		kategori_ad=:ad,
		kategori_durum=:kategori_durum,
		kategori_seourl=:seourl,
		kategori_sira=:sira
		");
	$insert=$kaydet->execute(array(
		'ad' => $_POST['kategori_ad'],
		'kategori_durum' => $_POST['kategori_durum'],
		'seourl' => $kategori_seourl,
		'sira' => $_POST['kategori_sira']
		));

	if ($insert) {

		Header("Location:../production/kategori.php?durum=ok");

	} else {

		Header("Location:../production/kategori.php?durum=no");
	}

}




if (@$_GET['kategorisil']=="ok") {

	$sil=$db->prepare("DELETE from kategori where kategori_id=:kategori_id");
	$kontrol=$sil->execute(array(
		'kategori_id' => $_GET['kategori_id']
		));

	if ($kontrol) {

		Header("Location:../production/kategori.php?durum=ok");

	} else {

		Header("Location:../production/kategori.php?durum=no");
	}

}



if (@$_GET['urunsil']=="ok") {

	$sil=$db->prepare("DELETE from urun where urun_id=:urun_id");
	$kontrol=$sil->execute(array(
		'urun_id' => $_GET['urun_id']
		));

	if ($kontrol) {

		Header("Location:../production/urun.php?durum=ok");

	} else {

		Header("Location:../production/urun.php?durum=no");
	}

}



if (isset($_POST['urunekle'])) {

	$urun_seourl=seo($_POST['urun_ad']);

	$kaydet=$db->prepare("INSERT INTO urun SET
		kategori_id=:kategori_id,
		urun_ad=:urun_ad,
		urun_detay=:urun_detay,
		urun_fiyat=:urun_fiyat,

		urun_keyword=:urun_keyword,
		urun_durum=:urun_durum,
		urun_stok=:urun_stok,
		urun_seourl=:seourl
		");
	$insert=$kaydet->execute(array(
		'kategori_id' => $_POST['kategori_id'],
		'urun_ad' => $_POST['urun_ad'],
		'urun_detay' => $_POST['urun_detay'],
		'urun_fiyat' => $_POST['urun_fiyat'],

		'urun_keyword' => $_POST['urun_keyword'],
		'urun_durum' => $_POST['urun_durum'],
		'urun_stok' => $_POST['urun_stok'],
		'seourl' => $urun_seourl

		));

	if ($insert) {

		Header("Location:../production/urun.php?durum=ok");

	} else {

		Header("Location:../production/urun.php?durum=no");
	}

}





if (isset($_POST['urunduzenle'])) {

	$urun_id=$_POST['urun_id'];
	$urun_seourl=seo($_POST['urun_ad']);

	$kaydet=$db->prepare("UPDATE urun SET
		kategori_id=:kategori_id,
		urun_ad=:urun_ad,
		urun_detay=:urun_detay,
		urun_fiyat=:urun_fiyat,
    urun_onecikar=:urun_onecikar,
		urun_keyword=:urun_keyword,
		urun_durum=:urun_durum,
		urun_stok=:urun_stok,
		urun_seourl=:seourl
		WHERE urun_id={$_POST['urun_id']}");
	$update=$kaydet->execute(array(
		'kategori_id' => $_POST['kategori_id'],
		'urun_ad' => $_POST['urun_ad'],
		'urun_detay' => $_POST['urun_detay'],
		'urun_fiyat' => $_POST['urun_fiyat'],
    'urun_onecikar' => $_POST['urun_onecikar'],
		'urun_keyword' => $_POST['urun_keyword'],
		'urun_durum' => $_POST['urun_durum'],
		'urun_stok' => $_POST['urun_stok'],
		'seourl' => $urun_seourl

		));

	if ($update) {

		Header("Location:../production/urun-duzenle.php?durum=ok&urun_id=$urun_id");

	} else {

		Header("Location:../production/urun-duzenle.php?durum=no&urun_id=$urun_id");
	}

}



if (isset($_POST['sepetekle'])) {


	$ayarekle=$db->prepare("INSERT INTO sepet SET
		urun_adet=:urun_adet,
		kullanici_id=:kullanici_id,
		urun_id=:urun_id

		");

	$insert=$ayarekle->execute(array(
		'urun_adet' => $_POST['urun_adet'],
		'kullanici_id' => $_POST['kullanici_id'],
		'urun_id' => $_POST['urun_id']

		));


	if ($insert) {

		Header("Location:../../sepet?durum=ok");

	} else {

		Header("Location:../../sepet?durum=no");
	}

}




if (@$_GET['urun_onecikar']=="ok") {




	$duzenle=$db->prepare("UPDATE urun SET

		urun_onecikar=:urun_onecikar

		WHERE urun_id={$_GET['urun_id']}");

	$update=$duzenle->execute(array(


		'urun_onecikar' => $_GET['urun_one']
		));



	if ($update) {



		Header("Location:../production/urun.php?durum=ok");

	} else {

		Header("Location:../production/urun.php?durum=no");
	}

}




if (isset($_POST['bankaekle'])) {

	$kaydet=$db->prepare("INSERT INTO banka SET
		banka_ad=:ad,
		banka_durum=:banka_durum,
		banka_hesapadsoyad=:banka_hesapadsoyad,
		banka_iban=:banka_iban
		");
	$insert=$kaydet->execute(array(
		'ad' => $_POST['banka_ad'],
		'banka_durum' => $_POST['banka_durum'],
		'banka_hesapadsoyad' => $_POST['banka_hesapadsoyad'],
		'banka_iban' => $_POST['banka_iban']
		));

	if ($insert) {

		Header("Location:../production/banka.php?durum=ok");

	} else {

		Header("Location:../production/banka.php?durum=no");
	}

}


if (isset($_POST['bankaduzenle'])) {

	$banka_id=$_POST['banka_id'];

	$kaydet=$db->prepare("UPDATE banka SET

		banka_ad=:ad,
		banka_durum=:banka_durum,
		banka_hesapadsoyad=:banka_hesapadsoyad,
		banka_iban=:banka_iban
		WHERE banka_id={$_POST['banka_id']}");
	$update=$kaydet->execute(array(
		'ad' => $_POST['banka_ad'],
		'banka_durum' => $_POST['banka_durum'],
		'banka_hesapadsoyad' => $_POST['banka_hesapadsoyad'],
		'banka_iban' => $_POST['banka_iban']
		));

	if ($update) {

		Header("Location:../production/banka-duzenle.php?banka_id=$banka_id&durum=ok");

	} else {

		Header("Location:../production/banka-duzenle.php?banka_id=$banka_id&durum=no");
	}




}


if (@$_GET['bankasil']=="ok") {

	$sil=$db->prepare("DELETE from banka where banka_id=:banka_id");
	$kontrol=$sil->execute(array(
		'banka_id' => $_GET['banka_id']
		));

	if ($kontrol) {


		Header("Location:../production/banka.php?durum=ok");

	} else {

		Header("Location:../production/banka.php?durum=no");
	}

}




//Sipariş İşlemleri

if (isset($_POST['bankasiparisekle'])) {


	$siparis_tip="Banka Havalesi";


	$kaydet=$db->prepare("INSERT INTO siparis SET
		kullanici_id=:kullanici_id,
		siparis_tip=:siparis_tip,
		siparis_banka=:siparis_banka,
		siparis_toplam=:siparis_toplam
		");
	$insert=$kaydet->execute(array(
		'kullanici_id' => $_POST['kullanici_id'],
		'siparis_tip' => $siparis_tip,
		'siparis_banka' => $_POST['siparis_banka'],
		'siparis_toplam' => $_POST['siparis_toplam']
		));

	if ($insert) {

		//Sipariş başarılı kaydedilirse...

		echo $siparis_id = $db->lastInsertId();

		echo "<hr>";


		$kullanici_id=$_POST['kullanici_id'];
		$sepetsor=$db->prepare("SELECT * FROM sepet where kullanici_id=:id");
		$sepetsor->execute(array(
			'id' => $kullanici_id
			));

		while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)) {

			$urun_id=$sepetcek['urun_id'];
			$urun_adet=$sepetcek['urun_adet'];

			$urunsor=$db->prepare("SELECT * FROM urun where urun_id=:id");
			$urunsor->execute(array(
				'id' => $urun_id
				));

			$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);

			echo $urun_fiyat=$uruncek['urun_fiyat'];



			$kaydet=$db->prepare("INSERT INTO siparis_detay SET

				siparis_id=:siparis_id,
				urun_id=:urun_id,
				urun_fiyat=:urun_fiyat,
				urun_adet=:urun_adet
				");
			$insert=$kaydet->execute(array(
				'siparis_id' => $siparis_id,
				'urun_id' => $urun_id,
				'urun_fiyat' => $urun_fiyat,
				'urun_adet' => $urun_adet

				));


		}

		if ($insert) {



			//Sipariş detay kayıtta başarıysa sepeti boşalt

			$sil=$db->prepare("DELETE from sepet where kullanici_id=:kullanici_id");
			$kontrol=$sil->execute(array(
				'kullanici_id' => $kullanici_id
				));


			Header("Location:../../siparislerim?durum=ok");
			exit;


		}






	} else {

		echo "başarısız";

		//Header("Location:../production/siparis.php?durum=no");
	}



}


if(isset($_POST['urunfotosil'])) {

	$urun_id=$_POST['urun_id'];


	echo $checklist = $_POST['urunfotosec'];


	foreach($checklist as $list) {

		$sil=$db->prepare("DELETE from urunfoto where urunfoto_id=:urunfoto_id");
		$kontrol=$sil->execute(array(
			'urunfoto_id' => $list
			));
	}

	if ($kontrol) {

		Header("Location:../production/urun-galeri.php?urun_id=$urun_id&durum=ok");

	} else {

		Header("Location:../production/urun-galeri.php?urun_id=$urun_id&durum=no");
	}


}





//Sipariş İşlemleri

if (isset($_POST['bankasiparisekle'])) {
//  echo $_POST['banka_id'];
  //exit;


	$siparis_tip="Banka Havalesi";


	$kaydet=$db->prepare("INSERT INTO siparis SET
		kullanici_id=:kullanici_id,
		siparis_tip=:siparis_tip,
		siparis_banka=:siparis_banka,
		siparis_toplam=:siparis_toplam
		");
	$insert=$kaydet->execute(array(
		'kullanici_id' => $_POST['kullanici_id'],
		'siparis_tip' => $siparis_tip,
		'siparis_banka' => $_POST['siparis_banka'],
		'siparis_toplam' => $_POST['siparis_toplam']
		));

	if ($insert) {

		//Sipariş başarılı kaydedilirse...

		echo $siparis_id = $db->lastInsertId();

		echo "<hr>";


		$kullanici_id=$_POST['kullanici_id'];
		$sepetsor=$db->prepare("SELECT * FROM sepet where kullanici_id=:id");
		$sepetsor->execute(array(
			'id' => $kullanici_id
			));

		while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)) {

			$urun_id=$sepetcek['urun_id'];
			$urun_adet=$sepetcek['urun_adet'];

			$urunsor=$db->prepare("SELECT * FROM urun where urun_id=:id");
			$urunsor->execute(array(
				'id' => $urun_id
				));

			$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);

			echo $urun_fiyat=$uruncek['urun_fiyat'];



			$kaydet=$db->prepare("INSERT INTO siparis_detay SET

				siparis_id=:siparis_id,
				urun_id=:urun_id,
				urun_fiyat=:urun_fiyat,
				urun_adet=:urun_adet
				");
			$insert=$kaydet->execute(array(
				'siparis_id' => $siparis_id,
				'urun_id' => $urun_id,
				'urun_fiyat' => $urun_fiyat,
				'urun_adet' => $urun_adet

				));


		}

		if ($insert) {



			//Sipariş detay kayıtta başarıysa sepeti boşalt

			$sil=$db->prepare("DELETE from sepet where kullanici_id=:kullanici_id");
			$kontrol=$sil->execute(array(
				'kullanici_id' => $kullanici_id
				));


			Header("Location:../../siparislerim?durum=ok");
			exit;


		}






	} else {

		echo "başarısız";

		//Header("Location:../production/siparis.php?durum=no");
	}



}




?>
