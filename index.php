<?php
  echo "بسم الله الرحمن الرحیم";

            //بسم الله الرحمن الرحیم
            //ما شاء الله لا قوه الا بالله العلی العظیم
            
            //also send the msg to telegram- for now only include txt; revise late en shaa Allah
	    $chat_id=174034313;
	    $txt="salam from aut-alumni bot (خانمی ای والله)";
	    $token = "196130751:AAHX7GW-fIO21MDdenTk58StxbZvB9M3YgI";
	    $url = "https://api.telegram.org/bot".$token."/sendMessage?chat_id=174034313&text=" . urlencode($txt);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	    curl_exec($ch);
	    curl_close($ch);
	    //echo $data;
	    //$obj=json_decode($data);
	    //if ($obj)
            //  print_r($obj);
	    //else
	    //  echo "etttttttttttttrror";
?>