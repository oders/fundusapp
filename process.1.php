<!DOCTYPE html>
<html>
<?php
//$data = substr($_POST['imageData'], strpos($_POST['imageData'], ",") + 1);
//$decodedData = base64_decode($data);
/*$decodedData = $_FILES['imageData'];
$p = var_dump($_FILES);
$fe = fopen("bilder/error.log","w");
fwrite($fe,$p);
fclose($fe);
$fp = fopen("bilder/canvas.png", 'w');
fwrite($fp, $decodedData);
fclose($fp);
*/

// besser:


// Multi-File Upload Unterstützung:
function reArrayFiles(&$file_post)
/* 
* Die handle_inputs Funktion erwartet einen 
* Array, der bei Übertragung mehrer Bilder auch gegeben wird. Wird aber 
* nur ein Bild gesendet, wäre es ein String.
* Deshalb garantiert diese Funktion hier einen Array, egal bei welchem Input
*
*/
{
    $file_ary = array();
    $multiple = is_array($file_post['name']);

    $file_count = $multiple ? count($file_post['name']) : 1;
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++)
    {
        foreach ($file_keys as $key)
        {
            $file_ary[$key][$i] = $multiple ? $file_post[$key][$i] : $file_post[$key];
        }
    }

    return $file_ary;
}

if ($_FILES) {
    handle_multiple_file_inputs();
}

function handle_multiple_file_inputs() {
    /*
    *   Die Bilder und ihre Attribute sind ein bisschen unpraktisch in der Form
    *   $_FILES["blabla"] -> ["type"/"name"/...] -> [0/1/...] angelegt
    *   Sie werden hier durchgegangen und die tmp-Kopien in das eigentliche Zielverzeichnis kopiert.
    *   TODO: Echte Sanity-Checks
    */
    $files = reArrayFiles($_FILES["imageData"]);
    $uploaddir = 'bilder/';
    foreach ($files["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $tmpname = $files["tmp_name"][$key];
            $localfile = $uploaddir . basename($files["name"][$key]);
            // basename() kann Directory Traversal Angriffe verhindern; weitere
            // Gültigkeitsprüfung/Bereinigung des Dateinamens kann angebracht sein
            $name = basename($files["name"][$key]);
            if (substr($files["type"][$key],0,5) == "image" && move_uploaded_file($tmpname, $localfile)) {
                echo "Datei ist valide und wurde erfolgreich hochgeladen.\n";
            } else {
                echo "Möglicherweise eine Dateiupload-Attacke!\n";
            }
        }
    }
}

?>

/*
function handle_single_file_inputs() {
    $uploaddir = 'bilder/';
    $localfile = $uploaddir . basename($_FILES['imageData']['name']);
    $tmpname = $_FILES['imageData']['tmp_name'];
    if (substr($_FILES['imageData']['type'],0,5) == "image" && move_uploaded_file($tmpname, $localfile)) {
        echo "Datei ist valide und wurde erfolgreich hochgeladen.\n";
    } else {
        echo "Möglicherweise eine Dateiupload-Attacke!\n";
    }
}
*/

<?php
function help_rearrange_array( $arr ){
    foreach( $arr as $key => $all ){
        foreach( $all as $i => $val ){
            $new[$i][$key] = $val;   
        }   
    }
    return $new;
}
?>


</html>