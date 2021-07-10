CREATE TABLE `node_tree`
(
    `idNode` int(11) NOT NULL auto_increment,
    `level`  int(11) NOT NULL,
    `iLeft`  int(11) NOT NULL,
    `iRight` int(11) NOT NULL,
    PRIMARY KEY (`idNode`),
    KEY `level` (`level`),
    KEY `iLeft` (`iLeft`),
    KEY `iRight` (`iRight`)
);

CREATE TABLE `node_tree_names`
(
    `idNode`   int(11)      NOT NULL,
    `language` varchar(255) NOT NULL,
    `nodeName` varchar(255) NOT NULL,
    KEY `idNode` (`idNode`),
    KEY `language` (`language`)
);