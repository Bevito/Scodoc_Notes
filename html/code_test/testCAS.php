Authentification en cours ...<br>
<?php
	$path = realpath($_SERVER['DOCUMENT_ROOT'] . '/..');
	require_once $path.'/includes/default_config.php';
	
	require_once $path . '/lib/CAS/CAS.php';
	require_once $path . '/config/cas_config.php';

	phpCAS::setDebug();
	phpCAS::setVerbose(true);
	
	phpCAS::proxy(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

	phpCAS::forceAuthentication();

	echo "C'est bien authentifié";
?>