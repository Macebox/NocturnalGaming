<?php

function create_button($url, $text, $class='smaller')
{
	$curl = create_url($url);
	return <<<EOD
<button class="{$class}" onclick="javascript:window.location='{$curl}';">{$text}</button>
EOD;
}

/**
 * Create HTML for a navbar.
 */
function getHTMLForNav($id)
{
	$mvc = CNocturnal::Instance();
	$p = $mvc->request->controller;
	$m = $mvc->request->method;
	$a = $mvc->request->arguments;
	foreach($mvc->config['navbar'] as $key => $item)
	{
		$selected = ($p == $item['url'] || $mvc->request->routing==$item['url']) ? " class='selected'" : null;
		@$html .= "<a href='{$mvc->request->CreateUrl($item['url'])}'{$selected}>{$item['text']}</a>\n";
	}
	return "<nav id='$id'>\n{$html}</nav>\n";
}

function login_menu_new()
{
	$mvc = CNocturnal::Instance();
	if($mvc->user['isAuthenticated'])
	{
		$items = "<img src='".get_gravatar(15)."'>";
		$items .= "<a href='" . create_url('user/profile') . "'>" . $mvc->user->GetAcronym() . "</a> ";
		if($mvc->user->InGroup($mvc->config['CMUser-Groups']['admin']['acronym']))
		{
			$items .= "<a href='" . create_url('acp') . "'>acp</a> ";
		}
		$items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
	}
	else if (isset($mvc->user['name']))
	{
		$items = "<span style='color: white;'>Prospect: ".$mvc->user['name']."</span>";
		if($mvc->user->InGroup($mvc->config['CMUser-Groups']['admin']['acronym']))
		{
			$items .= "<a href='" . create_url('acp') . "'>acp</a> ";
		}
		$items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
	}
	else
	{
		$items = "<a href='" . create_url('user/login') . "'>login</a> ";
	}
	return "<nav class=\"right\">$items</nav>";
}