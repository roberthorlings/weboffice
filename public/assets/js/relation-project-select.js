$(function() {
	function parseRelationProjectValue(value) {
		if(value.startsWith('klant.')) {
			matches = value.match( /klant\.(\d+)/);
			return { relatie_id: matches[1], project_id: null }
		} else if(value.startsWith('project.')) {
			matches = value.match( /project\.(\d+)\.(\d+)/);
			return { relatie_id: matches[1], project_id: matches[2] }
		} else {
			return { relatie_id: null, project_id: null };
		}
	}
	
	$('.relation-project-select').each(function(idx, el) {
		$(el).on('change', function() {
			$el = $(this);
			var name = $el.attr('name');
			
			var ids = parseRelationProjectValue($el.val());
			$( '.hidden-relation-' + name + '-relatie-id').val(ids.relatie_id);
			$( '.hidden-relation-' + name + '-project-id').val(ids.project_id);
		})
	})
})