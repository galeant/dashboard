<?php 
function cdn( $asset = '' ){
    // Verify if KeyCDN URLs are present in the config file
    if( !Config::get('app.cdn') )
        return asset( $asset );

    // Get file name incl extension and CDN URLs
    $cdns = Config::get('app.cdn');

    $assetName = basename( $asset );

    // Remove query string
    $assetName = explode("?", $assetName);
    $assetName = $assetName[0];

    // Select the CDN URL based on the extension
    foreach( $cdns as $cdn => $types ) {
        // dd(preg_match('/^.*\.(' . $types . ')$/i',$assetName));
        if( preg_match('/^.*\.(' . $types . ')$/i', $assetName) )
            return cdnPath($cdn, $asset);
    }

    // In case of no match use the last in the array
    end($cdns);
    // dd(cdnPath( key( $cdns ) , $asset));
    return cdnPath( key( $cdns ) , $asset);
}

function cdnPath($cdn, $asset) {
    return  "https://" . rtrim($cdn, "/") . "/" . ltrim( $asset, "/");
}