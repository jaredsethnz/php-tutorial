/**
 * Created by Seth on 12/06/16.
 */

//***************************************************************
// Thread Page Script
$(document).ready(function(){
    $('.reply').click(function(){
        var btnVal = $(this).attr('value');
        $('#td' + btnVal).hide(0)
        $('#tdForm' + btnVal).show(250);
    });

    $('.cancel').click(function(){
        var btnVal = $(this).attr('value');
        $('#td' + btnVal).show(0);
        $('#tdForm' + btnVal).hide(0);
    });

    $('#btnNewPost').click(function(){
        $('#divNewPost').show(250);
        $('#btnNewPost').hide(250);
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        return false;
    });

    $('#cancelNewPost').click(function(){
        $('#btnNewPost').show(250);
        $('#divNewPost').hide(250);
    });
});

//***************************************************************
// New Challenge Page Script
$(document).ready(function()
{
    $(document).on('submit', '#search', function()
    {
        var data = $(this).serialize();
        $.ajax({
            type : 'POST',
            url  : '/async/membersearch',
            data : data,
            success :  function(data)
            {
                $("#searchresults").html(data);
            }
        });
        return false;
    });

    $(document).on('submit', '.challengeUserForm', function()
    {
        $("#maxMemberAlert").html("");
        var memberCount = $('input:hidden[id=memberCount]').val();
        if (parseInt(memberCount) < 3) {
            var userNickName = $('input:hidden[name=userNickName]').val();
            var profilePic = $('input:hidden[name=profilePic]').val();
            var selectedUsers = $('input:hidden[id=membersToChallenge]').val();
            var selectedUsersNicks = selectedUsers.split(',');
            if ($.inArray(userNickName, selectedUsersNicks) === -1) {

                var profilePicVs = $('#profilePicVs').clone().html();
                profilePicVs += "<td> VS. </td><td><img src=" + profilePic + " width='100px' height='100px'></td>";

                var nickNameVs = $('#nickNameVs').clone().html();
                nickNameVs += "<td></td><td>" + userNickName + "</td>";

                $("#profilePicVs").html(profilePicVs);
                $("#nickNameVs").html(nickNameVs);
                $("#memberCount").val(parseInt(memberCount) + 1);
                selectedUsers += "," + userNickName;
                $("#membersToChallenge").val(selectedUsers);
            }
            else
            {
                $("#maxMemberAlert").html("* Member already added to challenge.");
            }
        }
        else
        {
            $("#maxMemberAlert").html("* Max challenge opponents reached, click cancel to clear selections.");
        }

        return false;
    });

    $(document).on('reset', '#newChallenge', function()
    {
        $("#profilePicVs").html('<td><img src="{{ profilePic }}" width="100px" height="100px"></td>');
        $("#nickNameVs").html('<td>{{ nickName }}</td>');

        return true;
    });

    $('#searchNick').autocomplete({
        source: function (request, response) {
            $.ajax({
                type: "POST",
                url:"/async/memberautocomplete",
                data: request,
                success: response,
                dataType: 'json'
            });
        },
        autoFocus: true,
        minLength: 1,
        delay: 100
    });
});

//***************************************************************
// Forum Category Page
$(document).ready(function(){

    $('#btnNewThread').click(function(){
        $('#divCreateThread').show(200);
        $('#btnNewThread').hide(200);
    });

    $('#cancelNewThread').click(function(){
        $('#btnNewThread').show(200);
        $('#divCreateThread').hide(200);
    });
});