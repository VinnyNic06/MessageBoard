<!--ch 6 tut-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Message Board</title>
</head>
<body>
	<h1>Message Board</h1>
	<?php 
		if(isset($_GET["action"])){
			if((file_exists("MessageBoard/messages.txt")) && (filesize("MessageBoard/messages.txt") != 0)){
				//a button has been clicked on and the file messages.txt exists w content
				$MessageArray = file("MessageBoard/messages.txt");

				switch($_GET["action"]){
					case "Delete First":
						array_shift($MessageArray);
						break;
					case "Delete Last":
						array_pop($MessageArray);
						break;
					case "Delete Message":
						if(isset($_GET["message"])){
							array_splice($MessageArray, $_GET["message"], 1);
						}
						break;
					case "Remove Duplicates":
						$MessageArray = array_unique($MessageArray);
						$MessageArray = array_values($MessageArray);
						break;

				}//end of switch

				if(count($MessageArray) > 0){
					$NewMessages = implode($MessageArray);
					$MessageStore = fopen("MessageBoard/messages.txt", "wb");
					//check if the fle is not accessable
					if($MessageStore === false){
						echo "There was an error updating te message file!\n";
					}
					else{
						fwrite($MessageStore, $NewMessages);
						fclose($MessageStore);
					}
				}
				else{
					//if no messages delete file
					unlink("MessageBoard/messages.txt");
				}
			}

		}


		if((!file_exists("MessageBoard/messages.txt")) || (filesize("MessageBoard/messages.txt") == 0)){
			echo "<p>There are no messages posted.</p>\n";
		}
		else{
			//use the file() method to create an array out of each in the txt file
			$MessageArray = file("MessageBoard/messages.txt");
			echo "<table style = \"background-color: lightgray;\"border = \"1\" width = \"100%\">\n";

			//var that simply counts the number of items in the message array 
			$count = count($MessageArray);

			//every messsage that array has, we will loop to create a new <tr> element and create the content 
			for($x = 0; $x < $count; ++$x){
				$CurrMsg = explode("~", $MessageArray[$x]);
				echo "<tr>\n";

				echo "<td width = \"5%\" style = \"text-align: center; font-weight: bold;\">" , ($x + 1) , "</td>\n";

				echo "<td width = \"85%\"><span style = \"font-weight: bold;\">Subject:</span> ", htmlentities($CurrMsg[0]), "<br>\n"; 

				echo "<span style = \"font-weight: bold;\">Name:</span> ", htmlentities($CurrMsg[1]), "<br>\n"; 

				echo "<span style = \"font-weight: bold; text-decoration: underline;\">Message:</span><br>\n ", htmlentities($CurrMsg[2]), "</td>\n";

				echo "<td width = \"10%\" style = \"text-align: center;\">", "<a href = 'MessageBoard.php?" , "action=Delete%20Message&" , "message=$x'>" , "Delete This Message</a></td>\n"; 

				echo "</tr>\n";
			}
			echo "</table>\n";
		}		
	?>
	<p><a href="PostMessage.php">Post New Message</a></p>
	<p><a href="MessageBoard.php?action=Remove%20Duplicates">Remove Duplicate Message</a></p>
	<p><a href="MessageBoard.php?action=Delete%20First">Delete First Message</a></p>
	<p><a href="MessageBoard.php?action=Delete%20Last">Delete Last Message</a></p>

</body>
</html>