<!--suppress HtmlUnknownTag -->
<script type="text/html" id="tmpl-lct-shortcode-default-edit-form">

	<form class="lct-edit-shortcode-form">

		<h2 class="lct-edit-shortcode-form-title">Insert A Tel Link</h2>
		<br/>

		<div class="lct-edit-shortcode-form-required-attrs">
		</div>
		<br/>

		<div class="lct-edit-shortcode-form-standard-attrs">
		</div>
		<br/>

		<div>
			<a href="javascript:void(0);" onclick="jQuery('#lct-edit-shortcode-form-advanced-attrs').toggle();">Advanced Options</a>
		</div>
		<br/>

		<div id="lct-edit-shortcode-form-advanced-attrs" class="lct-edit-shortcode-form-advanced-attrs" style="display:none;">
		</div>

		<input id="lct_tel_link-update-shortcode" type="button" class="button-primary" value="Update Link"/>
		<input id="lct_tel_link-insert-shortcode" type="button" class="button-primary" value="Insert Link"/>&nbsp;&nbsp;&nbsp;
		<a id="lct_tel_link-cancel-shortcode" class="button" style="color:#bbb;" href="#">Cancel</a>

	</form>

</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-text">
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>

		<div style="font-size:11px; font-style:italic; color:#5A5A5A;width: 100%;">{{ data.description }}</div>
		<input type="text" name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}" value="{{ data.value }}"/>
	</div>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-url">
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
		<input type="url" name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}" value="{{ data.value }}" class="code"/>
	</div>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-textarea">
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
		<textarea name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}">{{ data.value }}</textarea>
	</div>

</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-select">
	<div style="font-size:11px; font-style:italic; color:#5A5A5A">{{ data.description }}</div>
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
		<select name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}">
			<# _.each( data.options, function( label, value ) { #>
				<option value="{{ value }}"
				<# if ( value == data.value ){ print('selected'); }; #>
					<# if (data.attr == 'id' && value == '') { print('disabled="disabled"')}; #>>{{ label }}</option>
						<# }); #>
		</select>
	</div>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-radio">
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}-{{ value }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
		<# _.each( data.options, function( label, value ) { #>
			<input id="lct-shortcode-attr-{{ data.attr }}-{{ value }}" type="radio" name="{{ data.attr }}" value="{{ value }}"
			<# if ( value == data.value ){ print('checked'); } #>>{{ label }}<br/>
				<# }); #>
	</div>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-checkbox">
	<input type="checkbox" name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}" value="true"
	<# var val = ! data.value && data.default != undefined ? data.default : data.value; if ('true' == data.value ){ print('checked'); } #>>
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-email">
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
		<input type="email" name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}" value="{{ data.value}}"/>
	</div>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-number">
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
		<input type="number" name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}" value="{{ data.value}}"/>
	</div>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-hidden">
	<div class="field-block">
		<input type="hidden" name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}" value="true"/>
	</div>
</script>

<script type="text/html" id="tmpl-lct-shortcode-ui-field-date">
	<div class="field-block">
		<label for="lct-shortcode-attr-{{ data.attr }}">{{ data.label }}
			<a href="#" onclick="return false;" class="lct_tooltip tooltip tooltip_{{data.action}}__{{data.attr}}" title="{{data.tooltip}}"><i class='fa fa-question-circle'></i></a></label>
		<input type="date" name="{{ data.attr }}" id="lct-shortcode-attr-{{ data.attr }}" value="{{ data.value }}"/>
	</div>
</script>
