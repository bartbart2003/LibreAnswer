-- Single-line version
-- CREATE TABLE `pack_examplepack` (`questionID` int(11) NOT NULL AUTO_INCREMENT, `question` text CHARACTER SET utf8 NOT NULL, `hint` text CHARACTER SET utf8 NOT NULL, `answer1` text CHARACTER SET utf8 NOT NULL, `answer2` text CHARACTER SET utf8 NOT NULL, `answer3` text CHARACTER SET utf8 NOT NULL, `answer4` text CHARACTER SET utf8 NOT NULL, `correctAnswer` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`questionID`));

CREATE TABLE `pack_examplepack` (
  `questionID` int(11) NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8 NOT NULL,
  `hint` text CHARACTER SET utf8 NOT NULL,
  `answer1` text CHARACTER SET utf8 NOT NULL,
  `answer2` text CHARACTER SET utf8 NOT NULL,
  `answer3` text CHARACTER SET utf8 NOT NULL,
  `answer4` text CHARACTER SET utf8 NOT NULL,
  `correctAnswer` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`questionID`)
);
