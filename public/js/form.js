// btd
(function ($, window) {

    var hostname = window.location.hostname.replace('www.', '');

    var dirty = false;

    function confirm() {
        var $this = $(this);
        $this.click(function () {
            return window.confirm($this.data('confirm'));
        });
    }

    function link() {
        if(this.hostname.replace('www.', '') === hostname) {
            return;
        }
        $(this).attr('target', '_blank');
    }

    function windowBeforeUnload(e) {
        if (dirty) {
            var message = 'You have unsaved changes.';
            e.returnValue = message;
            return message;
        }
    }

    function formDirty() {
        var $form = $(this);
        $form.data('dirty', false);
        $form.on('input', function () {
            dirty = true;
        });
        $form.on('submit', function () {
            $(window).unbind('beforeunload');
        });
    }

    function formPopup(e) {
        e.preventDefault();
        var url = $(this).prop('href');
        window.open(url, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=60,left=60,width=500,height=600");
    }

    function simpleCollection() {
        $('.collection-simple').collection({
            init_with_n_elements: 1,
            allow_up: false,
            allow_down: false,
            max: 400,
            add: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a>',
            remove: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-minus"></span></a>',
            add_at_the_end: false,
        });
    }

    function complexCollection() {
        $('.collection-complex').collection({
            init_with_n_elements: 1,
            allow_up: false,
            allow_down: false,
            max: 400,
            add: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a>',
            remove: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-minus"></span></a>',
            add_at_the_end: true,
            after_add: function(collection, element){
                $(element).find('.select2entity').select2entity();
                $(element).find('.select2-container').css('width', '100%');
                return true;
            },
        });
    }

    function ckeditor() {
        if (window.CKEDITOR) {
            for (var key in window.CKEDITOR.instances) {
                var instance = window.CKEDITOR.instances[key];
                instance.on('mode', function () {
                    if (this.mode === 'source') {
                        var editable = instance.editable();
                        editable.attachListener(editable, 'input', function () {
                            dirty = true;
                        });
                    }
                });
                instance.on('change', function () {
                    dirty = true;
                });
            }
        }
    }


    $(document).ready(function () {
        $(window).bind('beforeunload', windowBeforeUnload);
        $('form').each(formDirty);
        $("a.popup").click(formPopup);
        $("a").each(link);
        $("*[data-confirm]").each(confirm);
        $('[data-toggle="popover"]').popover(); // add this line to enable boostrap popover
        if (typeof $().collection === 'function') {
            simpleCollection();
            complexCollection();
        }
        ckeditor();
    });

})(jQuery, window);
