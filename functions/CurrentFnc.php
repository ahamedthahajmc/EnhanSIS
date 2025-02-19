<?php


function UserInstitute()
{
	return $_SESSION['UserInstitute'];
}
function UserSyear()
{
	return $_SESSION['UserSyear'];
}
function UserMP()
{
	return $_SESSION['UserMP'];
}
// DEPRECATED
function UserPeriod()
{
	return $_SESSION['UserPeriod'];
}
function CpvId()
{
	return $_SESSION['CpvId'];
}

function CpvId_attn()
{
	return $_SESSION['CpvId_attn'];
}
function UserCoursePeriod()
{
	return $_SESSION['UserCoursePeriod'];
}

function UserSubject()
{
	return $_SESSION['UserSubject'];
}
function UserCourse()
{
	return $_SESSION['UserCourse'];
}


function UserStudentID()
{
	if(User('PROFILE')=='student')
	return $_SESSION['STUDENT_ID'];
	else
	return $_SESSION['student_id'];
}

function UserStaffID()
{
	return $_SESSION['staff_id'];
}

function UserID()
{
	return $_SESSION['STAFF_ID'];
}
function UserProfileID()
{
	return $_SESSION['PROFILE_ID'];
}

?>