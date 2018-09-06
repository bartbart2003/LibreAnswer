<?php
class databaseConnector
{
	# SERVER CONFIG #
	private $servername = '';
	private $username = '';
	private $password = '';
	private $database = '';
	private $adminPassword = '';
	# END OF CONFIG #
	
	# Connection variable
	public $conn;
	function connectToDatabase()
	{
		# Database connection
		$this->conn = new mysqli($this->servername, $this->username, $this->password);
		$this->conn->select_db($this->database);
		$utf8Query = 'SET NAMES utf8;';
		$this->conn->query($utf8Query);
	}
	
	function isValidAdminPassword($passwordToCheck)
	{
		if ($passwordToCheck == $this->adminPassword)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

class questionPacksManager
{
	public function getQuestionPacks()
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "SELECT * FROM packlist";
		$results = $dbConnector->conn->query($query);
		return $results;
	}
	
	public function deleteQuestionPack($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("DELETE FROM packlist WHERE packname=?;");
		$query->bind_param('s', $packname);
		$results = $query->execute();
		$query = "DROP TABLE IF EXISTS pack_".$packname."_abcd, pack_".$packname."_tf, pack_".$packname."_info;";
		$results = $dbConnector->conn->query($query);
	}
	
	public function importQuestionPack($packLocation, $packFile)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		if ($packLocation == 'remote')
		{
				$csvImportFile = file($packFile);
		}
		if ($packLocation == 'local')
		{
				$csvImportFile = file('import/'.$packFile);
		}
		$i = 0;
		$packImport_packname = '';
		$insertQuery = '';
		foreach($csvImportFile as $line)
		{
			if ($line !== '' && $line !== "\n")
			{
				if (substr($line, 0, 1) == '#')
				{
					// IF: Comment
				}
				else
				{
					// IF: Not comment
					if ($i == 0)
					{
						$packImport_packname = str_getcsv($line)[0];
						if ($packImport_packname == '')
						{
							return 'eemptyname';
						}
						$quePacksManager = new questionPacksManager();
						$quePacksManager->deleteQuestionPack($packImport_packname);
						$query = $dbConnector->conn->prepare("INSERT INTO packlist (packname, packDisplayName, packIconType, packIcon, packAuthor, packDescription, packLanguage, packAttributes) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
						$query->bind_param('ssssssss', $packImport_packname, str_getcsv($line)[1], str_getcsv($line)[2], str_getcsv($line)[3], str_getcsv($line)[4], str_getcsv($line)[5], str_getcsv($line)[6], str_getcsv($line)[7]);
						$results = $query->execute();
						$query = "CREATE TABLE IF NOT EXISTS pack_".$packImport_packname."_abcd (`ID` int(11) NOT NULL, `contentType` text CHARACTER SET utf8 NOT NULL, `multimediaType` text CHARACTER SET utf8 NOT NULL, `multimediaContent` text CHARACTER SET utf8 NOT NULL, `question` text CHARACTER SET utf8 NOT NULL, `answer1` text CHARACTER SET utf8 NOT NULL, `answer2` text CHARACTER SET utf8 NOT NULL, `answer3` text CHARACTER SET utf8 NOT NULL, `answer4` text CHARACTER SET utf8 NOT NULL, `hint` text CHARACTER SET utf8 NOT NULL, `correctAnswer` text CHARACTER SET utf8 NOT NULL, `questionExtra` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`ID`));";
						$results = $dbConnector->conn->query($query);
						$query = "CREATE TABLE IF NOT EXISTS pack_".$packImport_packname."_tf (`ID` int(11) NOT NULL, `contentType` text CHARACTER SET utf8 NOT NULL, `multimediaType` text CHARACTER SET utf8 NOT NULL, `multimediaContent` text CHARACTER SET utf8 NOT NULL, `question` text CHARACTER SET utf8 NOT NULL, `answer1` text CHARACTER SET utf8 NOT NULL, `answer2` text CHARACTER SET utf8 NOT NULL, `answer3` text CHARACTER SET utf8 NOT NULL, `answer4` text CHARACTER SET utf8 NOT NULL, `hint` text CHARACTER SET utf8 NOT NULL, `correctAnswer` text CHARACTER SET utf8 NOT NULL, `questionExtra` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`ID`));";
						$results = $dbConnector->conn->query($query);
						$query = "CREATE TABLE IF NOT EXISTS pack_".$packImport_packname."_info (`ID` int(11) NOT NULL, `contentType` text CHARACTER SET utf8 NOT NULL, `title` text CHARACTER SET utf8 NOT NULL, `description` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`ID`));";
						$results = $dbConnector->conn->query($query);
					}
					else
					{
						if (str_getcsv($line)[0] == 'abcd' || str_getcsv($line)[0] == 'tf')
						{
							$query1 = $dbConnector->conn->prepare("INSERT INTO pack_".$packImport_packname."_".str_getcsv($line)[0]." (ID, contentType, multimediaType, multimediaContent, question, answer1, answer2, answer3, answer4, hint, correctAnswer, questionExtra) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
							$query1->bind_param('isssssssssss', $i, str_getcsv($line)[0], str_getcsv($line)[1], str_getcsv($line)[2], str_getcsv($line)[3], str_getcsv($line)[4], str_getcsv($line)[5], str_getcsv($line)[6], str_getcsv($line)[7], str_getcsv($line)[8], str_getcsv($line)[9], str_getcsv($line)[10]);
							$results = $query1->execute();
						}
						if (str_getcsv($line)[0] == 'info')
						{
							$query2 = $dbConnector->conn->prepare("INSERT INTO pack_".$packImport_packname."_info (ID, contentType, title, description) VALUES (?, ?, ?, ?);");
							$query2->bind_param('isss', $i, str_getcsv($line)[0], str_getcsv($line)[1], str_getcsv($line)[2]);
							$results = $query2->execute();
						}
					}
					$i++;
				}
			}
		}
		return 'ok';
	}
}
?>
