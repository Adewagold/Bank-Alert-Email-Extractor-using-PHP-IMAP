<?php 
require ('email.php');

$host = 'mail.myschoolpay.com.ng';
$user = USERNAME;
$pass = PASSWORD;
$port = 143;
$ssl = false;
$folder = 'INBOX';
/* connect to gmail */
$hostname = $host.':'.$port.'/novalidate-cert}'.$folder;
$username = $user;
$password = $pass;

/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox,'ALL');

/* if emails are returned, cycle through each... */
if($emails) {
	
	/* begin output var */
	$output_message = '';
	
	/* put the newest emails on top */
	rsort($emails);
	
	/* for every email... */
	foreach($emails as $email_number) {
		
		/* get information specific to this email */
		$overview = imap_fetch_overview($inbox,$email_number,0);
		$message = imap_fetchbody($inbox,$email_number,2);
        $structure = imap_fetchstructure($inbox, $email_number);
        

        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $part = $structure->parts[1];
            $message = imap_fetchbody($inbox,$email_number,1);

            if($part->encoding == 3) {
                $message = quoted_printable_decode(imap_utf8($message));
                
            } else if($part->encoding == 1) {
                $message = quoted_printable_decode(imap_8bit($message));
            } else {
                $message = quoted_printable_decode(imap_8bit($message));
            }
        }

        ?>
        <html>
            
            <head> 
            <title>Email Extractor</title>
            </head>
            
<body>
<h1 style="color:magenta;">Extracted Email Message From 
<?php 
echo $overview[0]->from;
$int;
echo "No.".$int+=1;
?>
</h1>
<table border=1>
<tr>
<th style="width:150px">Status</th>
<th style="width:150px"> Amount </th>
<th style="width:350px">Description</th>
<tr>
<tr>

<td><?php 
preg_match("/[D][E][A-Z]+]|[C][R][A-Z]+|[D][e][a-z][:]/",$overview[0]->subject,$debitCredit);
    print_r($debitCredit[0]);
?></td>
<td> <?php         
        preg_match('/([N]\d*,*\d*.\d+ DR|[N]+\d*,*\d*.\d+ CR)/', $message, $amount);
        $str = preg_replace('/\s+[C][R]|\s+[D][R]|[,]|[N]/', '', $amount[1]);
        $format = "";
// This will echo the extracted credit amount to number format
            echo number_format($str,2);?>
          
        </td>
<td><?php 
       preg_match("/FIP:[A-Za-z]*\W*[a-zA-Z]*|FBN\/[A-Za-z]*\W*[a-zA-Z]*|MOBILE [a-zA-Z]*\W*\w*\W*\d*|ONLINE [a-zA-Z]*\W*\w*\W*\d*/",$message,$description);
        echo($description[0]);
?></td>
<tr>
<tr>


</table>
</body>
</html>
        <?php
		/* output the email header information */
		$output_message.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output_message.= '<span class="subject"> '.$overview[0]->subject.'</span> ';
		$output_message.= '<span class="from">'.$overview[0]->from.'</span>';
		$output_message.= '<span class="date">on '.$overview[0]->date.'</span>';
		$output_message.= '</div>';
	
		/* output the email body */
		$output_message.= '<div class="body">'.$message.'</div>';
		
	}
	


//echo($output);
		
  //   var_dump (strip_tags($output));
} 


/* close the connection */
imap_close($inbox);

?>

