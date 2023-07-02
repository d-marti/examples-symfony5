var container = jQuery('.js-topic-vote-arrows');
container.find('a').on('click', function(e) {
    e.preventDefault();
    var link = jQuery(e.currentTarget);

    jQuery.ajax({
        url: '/api/topics/' + container.data('topic') + '/vote/' + link.data('direction'),
        method: 'PUT'
    }).then(function(data) {
        container.find('.js-topic-vote-total').text(data.votes);
    });
});
