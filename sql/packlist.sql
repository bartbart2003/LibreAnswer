CREATE TABLE `packlist` (
  `packID` int(11) NOT NULL AUTO_INCREMENT,
  `packname` text CHARACTER SET utf8 NOT NULL,
  `packDisplayName` text CHARACTER SET utf8 NOT NULL,
  `packIconType` text CHARACTER SET utf8 NOT NULL,
  `packIcon` text CHARACTER SET utf8 NOT NULL,
  `packAuthor` text CHARACTER SET utf8 NOT NULL,
  `packDescription` text CHARACTER SET utf8 NOT NULL,
  `packLanguage` text CHARACTER SET utf8 NOT NULL,
  `packAttributes` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`packID`)
);
