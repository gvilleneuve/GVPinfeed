<?php

class GVPinfeed {

	/**
	 * @param string $user
	 *
	 * @throws Exception
	 * @return array
	 */
	public function get_user_info( $user ) {

		$xml_source = file_get_contents( "http://www.pinterest.com/$user/feed.rss" );
		$x          = simplexml_load_string( $xml_source );

		if ( count( $x ) == 0 ) {
			throw new Exception( "{$user} does not exist" );
		}

		$user                  = array();
		$user['name']          = (string) $x->channel->title;
		$user['link']          = (string) $x->channel->link;
		$user['description']   = (string) $x->channel->description;
		$user['lang']          = (string) $x->channel->language;
		$user['lastBuildDate'] = (string) $x->channel->lastBuildDate;

		return $user;
	}

	/**
	 * @param string $user
	 * @param bool   $board
	 * @param int    $howMany
	 *
	 * @return array|bool
	 */
	public function get_pins_for_user( $user, $board = false, $howMany = 10 ) {

		if ( ! $board ) {
			$xml_source = file_get_contents( "http://www.pinterest.com/$user/feed.rss" );
		}
		else {
			$xml_source = file_get_contents( "http://www.pinterest.com/$user/$board.rss" );
		}
		$x = simplexml_load_string( $xml_source );
		if ( count( $x ) == 0 ) {
			return false;
		}

		$items = array();
		$i     = 0;
		foreach ( $x->channel->item as $item ) {

			if ( $i < $howMany ) {
				$post                = array();
				$post['date']        = date( "F j, Y", strtotime( $item->pubDate ) );
				$post['time_ago']    = $this->time_ago( $item->pubDate );
				$post['link']        = (string) $item->link;
				$post['title']       = (string) $item->title;
				$post['board']       = (string) $x->channel->title;
				$post['description'] = (string) $item->description;
				$post['text']        = $this->add_links( strip_tags( $item->description ) );

				$dom = new \DomDocument( '1.0', 'UTF-8' );
				if ( @$dom->loadHTML( $item->description ) ) {

					$dom->preserveWhiteSpace = false;
					$images                  = $dom->getElementsByTagName( 'img' );
					foreach ( $images as $image ) {
						$post['image_192'] = $image->getAttribute( 'src' );
						$post['image_236'] = str_ireplace( "192x", "236x", $post['image_192'] );
						$post['image_736'] = str_ireplace( "192x", "736x", $post['image_192'] );
						break;
					}
				}
				unset( $dom );
				array_push( $items, $post );
				$i ++;
			}
			else {
				break;
			}
		}

		return $items;
	}

	/**
	 * @param string $date
	 *
	 * @return string
	 */
	public function time_ago( $date ) {

		$timeAgoInSeconds = strtotime( 'now' ) - strtotime( $date );

		$minute = 60;
		$hour   = 3600;
		$day    = 86400;

		if ( $timeAgoInSeconds < $minute ) {
			$ago = "less than a minute ago";
		}
		elseif ( $timeAgoInSeconds < $hour ) {
			$minutesAgo = floor( $timeAgoInSeconds / $minute );
			if ( $minutesAgo > 1 ) {
				$ago = $minutesAgo . " minutes ago";
			}
			else {
				$ago = $minutesAgo . " minute ago";
			}
		}
		elseif ( $timeAgoInSeconds < $day ) {
			$hoursAgo = floor( $timeAgoInSeconds / $hour );
			if ( $hoursAgo > 1 ) {
				$ago = $hoursAgo . " hours ago";
			}
			else {
				$ago = $hoursAgo . " hour ago";
			}
		}
		else {
			$daysAgo = floor( $timeAgoInSeconds / $day );
			if ( $daysAgo > 1 ) {
				$ago = $daysAgo . " days ago";
			}
			else {
				$ago = $daysAgo . " day ago";
			}
		}

		return $ago;
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public function add_links( $string ) {
		// Convert URLs into hyperlinks
		$string = preg_replace(
			'@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@',
			'<a target="_blank" href="$1">$1</a>',
			$string
		);

		// Convert usernames (@) into links
		$string = preg_replace(
			"(@([a-zA-Z0-9\_]+))",
			"<a target='_blank' href=\"https://pinterest.com/\\1\">\\0</a>",
			$string
		);

		// Convert hash tags (#) to links
		$string = preg_replace(
			'/(^|\s)#(\w+)/',
			'\1<a target="_blank" href="https://pinterest.com/search/?q=\2">#\2</a>',
			$string
		);

		return $string;
	}
}
