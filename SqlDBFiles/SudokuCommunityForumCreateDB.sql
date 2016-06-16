drop database if exists SudokuCommunityForum;

create database SudokuCommunityForum;

use SudokuCommunityForum;

create table User(
	userID int auto_increment not null primary key,
	nickName varchar(25) not null unique key,
    firstName varchar(25) not null,
    lastName varchar(25) not null,
	email varchar(45) not null unique key,
    challengeable boolean default false,
    rank tinyint default 1,
    joinDate date not null,
    password char(60) not null,
    activated boolean default false,
    activationHash char(60) not null,
    profilePic varchar(50)
);

create table SudokuBoard(
	boardID int primary key auto_increment,
    boardSize varchar(6) not null,
    difficulty tinyint not null,
    boardValues varchar(160)
);

create table ChallengeApproval(
	challengeApprovalID int primary key auto_increment,
    challengerNickName varchar(25) not null,
    duration int not null,
    boardSize char(3) not null,
    difficulty tinyint not null,
    challengeApproved boolean default null,
    foreign key (challengerNickName) references User (nickName)
);

create table UserChallengeApproval(
	challengeApprovalID int,
    userNickName varchar(25) not null,
    userApproval boolean default null,
    primary key (challengeApprovalID, userNickName),
    foreign key (challengeApprovalID) references ChallengeApproval (challengeApprovalID),
    foreign key (userNickName) references User (nickName)
);

create table ActiveChallenge(
	activeChallengeID int primary key auto_increment,
	boardID int not null,
    dateStart date not null,
    dateEnd date not null,
    challengeFinished boolean default false
);

create table UserActiveChallenge(
	activeChallengeID int,
    userNickName varchar(25) not null,
    completionTime time,
    forfeited boolean default false,
    primary key(activeChallengeID, userNickName),
    foreign key (activeChallengeID) references ActiveChallenge (activeChallengeID),
    foreign key (userNickName) references User (nickName)
);

create table ChallengeHistory(
	challengeHistoryID int primary key auto_increment,
    boardID int not null,
    dateArchived date not null,
    winnerNickName varchar(25) not null,
    foreign key (boardID) references SudokuBoard (boardID)
);

create table UserChallengeHistory(
	challengeHistoryID int auto_increment,
    userNickName varchar(25) not null,
    finishingNumber smallInt default 0,
    primary key (challengeHistoryID, userNickName),
    foreign key (challengeHistoryID) references ChallengeHistory (challengeHistoryID),
    foreign key (userNickName) references User (nickName)
);

create table DeclinedChallenge(
	declinedChallengeID int primary key auto_increment,
    dateDeclined date not null,
    challengerNickName varchar(25) not null
);

create table UserDeclinedChallenge(
	declinedChallengeID int auto_increment,
    challengerNickName varchar(25) not null,
    declined boolean default false,
    primary key (declinedChallengeID, challengerNickName),
    foreign key (declinedChallengeID) references DeclinedChallenge (declinedChallengeID),
    foreign key (challengerNickName) references User (nickName)
);

create table Category(
	categoryName varchar(50) not null primary key
);

create table Thread(
	threadID int auto_increment not null primary key,
    categoryName varchar(50) not null,
    nickName varchar(25) not null,
    title varchar(50) not null,
    threadDate date not null,
    foreign key (categoryName) references Category (categoryName),
    foreign key (nickName) references User (nickName)
);

create table Post(
	postID int auto_increment not null primary key,
    threadID int not null,
    nickName varchar(25) not null,
    postContent varchar(300) not null,
    postDate timestamp not null,
    foreign key (threadID) references Thread (threadID),
    foreign key (nickName) references User (nickName)
);

create table Reply(
	replyID int auto_increment not null primary key,
    postID int not null,
    nickName varchar(25) not null,
    replyContent varchar(300) not null,
    replyDate timestamp not null,
    foreign key (postID) references Post (postID),
    foreign key (nickName) references User (nickName)
);