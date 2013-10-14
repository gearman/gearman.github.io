// super super basic tab switcher for inline blocks of code. See examples/reverse/index.md
$(document).ready(function() {
    $('.code-tabs').each(function(idx, elm){
        var $this = $(elm);
        var $highlights = $this.find('.highlight');
        var $switcher = $('<p/>');
        var first = true;
        $highlights.each(function(idx2, elm2){
            var cln = $(elm2).find('code').get(0).className;
            var $a = $('<a href="#">' + cln + '</a>');
            $a.click(function(){
                $highlights.hide();
                $this.find('a').removeClass('active');
                $(elm2).show();
                $a.addClass('active');
                return false;
            });
            if (!first) {
                $(elm2).hide();
            }
            else {
                $a.addClass('active');
                first = false;
            }
            $switcher.append($a);
        });
        $this.prepend($switcher);
    });
});