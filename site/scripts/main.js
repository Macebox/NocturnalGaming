function bbcode_ins(fieldId, tag)
{
	field=document.getElementById(fieldId);
	if(tag == 'b' || tag == 'i' || tag == 'u' || tag == 'code' || tag == 'img'
	|| tag == 'u' || tag == 'quote' || tag == 'size' || tag == 'color')
	{
		if (document.selection)
		{
			field.focus();
			sel = document.selection.createRange();
			sel.text = '[' + tag + '][/' + tag+']';
		}
		//MOZILLA/NETSCAPE/SAFARI support
		else if (field.selectionStart || field.selectionStart == 0)
		{
			var startPos = field.selectionStart;
			var endPos = field.selectionEnd;
			field.focus();
			field.value = field.value.substring(0, startPos)
			+ '[' + tag + '][/' + tag+']'
			+ field.value.substring(endPos, field.value.length);
		}
	} else if (tag == 'url=')
	{
		if (document.selection)
		{
			field.focus();
			sel = document.selection.createRange();
			sel.text = '[' + tag + '=]'+sel.text+'[/' + tag+']';
		}
		//MOZILLA/NETSCAPE/SAFARI support
		else if (field.selectionStart || field.selectionStart == 0)
		{
			var startPos = field.selectionStart;
			var endPos = field.selectionEnd;
			field.focus();
			field.value = field.value.substring(0, startPos)
			+ '[' + tag + '][/' + tag+']'
			+ field.value.substring(endPos, field.value.length);
		}
	}
}