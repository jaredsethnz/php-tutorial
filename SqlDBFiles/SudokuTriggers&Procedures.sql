
use SudokuCommunityForum;

delimiter $$


# SudokuForum :: Triggers
# SudokuForum :: Trigger for checking approvals

drop trigger if exists CheckApprovals_au$$

create trigger CheckApprovals_au
after update on UserChallengeApproval
for each row
begin

set @userCount = ( select count(*) from UserChallengeApproval where challengeApprovalID = old.challengeApprovalID );
set @userApproved = ( select count(*) from UserChallengeApproval where challengeApprovalID = old.challengeApprovalID and userApproval = true );
set @userDenied = ( select count(*) from UserChallengeApproval where challengeApprovalID = old.challengeApprovalID and userApproval = false );

if ( @userCount = @userApproved ) then

update ChallengeApproval set challengeApproved = true where challengeApprovalID = old.challengeApprovalID;

end if;

if ( @userDenied > 0 ) then

update ChallengeApproval set challengeApproved = false where challengeApprovalID = old.challengeApprovalID;

end if;
end$$
  
  
# SudokuForum :: Trigger for checking approved challenge then creating a new active challenge

drop trigger if exists ActivateChallenge_au$$

create trigger ActivateChallenge_au
after update on ChallengeApproval
for each row
begin

set @approvalID = old.challengeApprovalID;

if ( new.challengeApproved = true ) then

set @randomBoardID = ( select boardID from SudokuBoard where boardSize = old.boardSize and difficulty = old.difficulty order by rand() limit 1 );
insert into ActiveChallenge values ( null, @randomBoardID, now(), now() + interval old.duration day, false );

set @activeChallengeID = ( select last_insert_id() );
insert into UserActiveChallenge ( activeChallengeID, userNickName, completionTime ) values ( @activeChallengeID, old.challengerNickName, null );
insert into UserActiveChallenge ( activeChallengeID, userNickName, completionTime )
select @activeChallengeID, userNickName, null from UserChallengeApproval where challengeApprovalID = old.challengeApprovalID;

end if;

if ( new.challengeApproved = false ) then

insert into DeclinedChallenge ( declinedChallengeID, challengerNickName, dateDeclined ) values ( null, old.challengerNickName, now() );
set @activeChallengeID = ( select last_insert_id() );
insert into UserDeclinedChallenge ( declinedChallengeID, challengerNickName, declined ) values ( @activeChallengeID, old.challengerNickName, false );
insert into UserDeclinedChallenge ( declinedChallengeID, challengerNickName, declined )
select @activeChallengeID, userNickName, ( case when userApproval = true then false else true end ) from UserChallengeApproval where challengeApprovalID = old.challengeApprovalID;

end if;

end$$
  
  
# SudokuForum :: Trigger for checking finished players

drop trigger if exists ActiveChallengeFinished_au$$

create trigger ActiveChallengeFinished_au
after update on UserActiveChallenge
for each row
begin

set @playerCount = ( select count(*) from UserActiveChallenge where activeChallengeID = old.activeChallengeID );
set @finishedPlayerCount = ( select count(*) from UserActiveChallenge where activeChallengeID = old.activeChallengeID and completionTime is not null );
if ( @playerCount = @finishedPlayerCount ) then

	update ActiveChallenge set challengeFinished = true where activeChallengeID = old.activeChallengeID;

end if;

end$$
  
# SudokuForum :: Trigger for adding finished challenge to history

drop trigger if exists ArchiveChallenge_au$$

create trigger ArchiveChallenge_au
after update on ActiveChallenge
for each row
begin

if ( new.challengeFinished = true ) then

	set @winner = ( select userNickName from UserActiveChallenge where activeChallengeID = old.activeChallengeID order by completionTime asc limit 1 );
	insert into ChallengeHistory ( challengeHistoryID, boardID, dateArchived, winnerNickName )
	values ( null, old.boardID, now(), @winner );
	
	set @ID = ( select last_insert_id() );
	set @row = 0;
	insert into UserChallengeHistory ( challengeHistoryID, userNickName, finishingNumber )
	select @ID, userNickName, ( @row := @row + 1 ) from UserActiveChallenge order by completionTime asc;

end if;
end$$


# SudokuForum :: Procedures
# SudokuForum :: delete all associated rows with approved challenges ( This is because they are automatically moved to ActiveChallenges )

drop procedure if exists deleteApprovedChallenges$$

create procedure deleteApprovedChallenges()
begin
set @approvedRowCount = ( select count(*) from ChallengeApproval where challengeApproved is not null );
while ( @approvedRowCount > 0 ) do

	set @ID = ( select challengeApprovalID from ChallengeApproval where challengeApproved is not null order by challengeApprovalID asc limit 1);
	delete from UserChallengeApproval where challengeApprovalID = @ID;
	delete from ChallengeApproval where challengeApprovalID = @ID;
	set @approvedRowCount = @approvedRowCount - 1;

end while;
end$$
  
  
# SudokuForum :: delete all associated rows with finished active challenges ( This is because they are automatically moved to ActiveChallenges )

drop procedure if exists deleteFinishedChallenges$$

create procedure deleteFinishedChallenges()
begin
set @finishedChallengeCount = ( select count(*) from ActiveChallenge where challengeFinished = true );
if ( @finishedChallengeCount > 0 ) then

	set @ID = ( select activeChallengeID from ActiveChallenge where challengeFinished = true order by activeChallengeID asc limit 1 );
	delete from UserActiveChallenge where activeChallengeID = @id;
	delete from ActiveChallenge where activeChallengeID = @ID;
	set @finishedChallengeCount = @finishedChallengeCount - 1;
	
end if;
end$$


delimiter ;
