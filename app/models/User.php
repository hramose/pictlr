<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function pictures()
	{
		return $this->hasMany('Picture');
	}

	public function liked_pictures()
	{
		return $this->belongsToMany('Picture');
	}

	public function comments()
	{
		return $this->hasMany('Comment');
	}

	public function followers()
	{
		return $this->hasMany('Follower');
	}

	public function following()
	{
		return $this->hasMany('Follower', 'follower_id');
	}

	public function getTimelinePictures()
	{
		$following_ids	= $this->following()->select('user_id')->get()->all();

		foreach($following_ids as $key => &$val)
			$val = $val->user_id;

		$pictures	= Picture::whereIn('user_id', $following_ids )->orWhere('user_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->get()->all();

		return $pictures;
	}
}