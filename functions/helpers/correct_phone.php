<?php
function correct_phone($phone)
{
	return preg_replace('/\D+/', '', $phone);
}