<?php 


$paths = [
	'',
	'lorem',
	'lorem/:id/create',
	'lorem/:id'
];

$uri = 'lorem/1';

$_path = NULL;
foreach ($paths as $key => $path) {
	if($path === $uri) {
		$_path = $path;
		break;
	} else {
		$schemes = explode('/', $uri);
		$pathSchemes = explode('/', $path);
		if(count($schemes) == count($pathSchemes)) {
			echo "Will attempt to review $path ";
			$match = true;
			foreach ($pathSchemes as $key => $scheme) {
				if($scheme !== $schemes[$key] && substr($scheme, 0, 1) !== ':') {
					$match = false;
					break;
				}
			}
			if($match) {
				$_path = $path;
			}
		}
	}
}

echo "Match: $_path";