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
class questionsManager
{
	public function getQuestionsCount($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = 'SELECT COUNT(*) as rowscount FROM pack_'.$packname;
		$results = $dbConnector->conn->query($query);
		$questionsCount = 0;
		while ($row = $results->fetch_assoc()) {
			$questionsCount = $row['rowscount'];
		}
		return $questionsCount;
	}
	
	public function getValidAnswer($questionID, $packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("SELECT * FROM pack_".$packname." WHERE questionID=?");
		$query->bind_param('s', $questionID);
		$query->execute();
		$results = $query->get_result();
		$row = $results->fetch_assoc();
		return $row['correctAnswer'];
	}
}
class userAnswersManager
{
	public function getUsersAnswers($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "SELECT * FROM answers_".$packname;
		$results = $dbConnector->conn->query($query);
		return $results;
	}
	
	public function getQuestionPackAnswers($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "SELECT correctAnswer FROM pack_".$packname;
		$results = $dbConnector->conn->query($query);
		return $results;
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
		$query = "DROP TABLE IF EXISTS pack_".$packname.";";
		$results = $dbConnector->conn->query($query);
	}
	
	public function importQuestionPack($packFilename)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$csvImportFile = file('import/'.$packFilename);
		$i = 0;
		$packImport_packname = '';
		$insertQuery = '';
		foreach($csvImportFile as $line)
		{
			if ($line !== '' && $line !== "\n")
			{
				// IF : Line not empty
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
							break;
						}
						else
						{
							$query = $dbConnector->conn->prepare("INSERT INTO packlist (packname, packDisplayName, packIconType, packIcon, packAuthor, packDescription, packLanguage, packLicense, packAttributes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
							$query->bind_param('sssssssss', $packImport_packname, str_getcsv($line)[1], str_getcsv($line)[2], str_getcsv($line)[3], str_getcsv($line)[4], str_getcsv($line)[5], str_getcsv($line)[6], str_getcsv($line)[7], str_getcsv($line)[8]);
							$results = $query->execute();
							$query = "CREATE TABLE IF NOT EXISTS pack_".$packImport_packname." (`questionID` int(11) NOT NULL AUTO_INCREMENT, `question` text CHARACTER SET utf8 NOT NULL, `multimediaType` text CHARACTER SET utf8 NOT NULL, `multimediaContent` text CHARACTER SET utf8 NOT NULL, `hint` text CHARACTER SET utf8 NOT NULL, `answer1` text CHARACTER SET utf8 NOT NULL, `answer2` text CHARACTER SET utf8 NOT NULL, `answer3` text CHARACTER SET utf8 NOT NULL, `answer4` text CHARACTER SET utf8 NOT NULL, `correctAnswer` text CHARACTER SET utf8 NOT NULL, `questionType` text CHARACTER SET utf8 NOT NULL, `questionExtra` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`questionID`));";
							$results = $dbConnector->conn->query($query);
							$insertQuery = $insertQuery."INSERT INTO pack_".$packImport_packname." (question, multimediaType, multimediaContent, hint, answer1, answer2, answer3, answer4, correctAnswer, questionType, questionExtra) VALUES";
						}
					}
					else
					{
						$insertQuery = $insertQuery." (\"".str_getcsv($line)[0]."\", \"".str_getcsv($line)[1]."\", \"".str_getcsv($line)[2]."\", \"".str_getcsv($line)[3]."\", \"".str_getcsv($line)[4]."\", \"".str_getcsv($line)[5]."\", \"".str_getcsv($line)[6]."\", \"".str_getcsv($line)[7]."\", \"".str_getcsv($line)[8]."\", \"".str_getcsv($line)[9]."\", \"".str_getcsv($line)[10]."\"),";
					}
					$i++;
				}
			}
		}
		if ($packImport_packname == '')
		{
			return 'eemptyname';
		}
		else
		{
			$insertQuery = substr($insertQuery, 0, -1).";";
			$dbConnector->conn->query($insertQuery);
			return 'ok';
		}
	}
	
	public function clearUserAnswers($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "TRUNCATE TABLE answers_".$packname;
		$results = $dbConnector->conn->query($query);
	}
	
	public function clearSingleAnswer($packname, $answerID)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("DELETE FROM answers_".$packname." WHERE answerID=?;");
		$query->bind_param('s', $answerID);
		$results = $query->execute();
	}
}
?>
