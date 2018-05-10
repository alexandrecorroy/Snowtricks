$(document).ready(function() {

    // Partie front picture
    $('#frontPicture').on('shown.bs.modal', function () {
        $('#frontPicture').trigger('focus')
    });

    function changeFrontPicture(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#front_picture').css('background-image', 'url('+e.target.result+')');
                $('#frontPicture').modal('toggle')
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#snowtricks_appbundle_trick_frontPicture").change(function(){
        changeFrontPicture(this);
    });

    $('#resetFrontPicture').click(function () {
        var2 = '/img/none.png';
        $('#front_picture').css('background-image', 'url('+var2+')');
        $("#snowtricks_appbundle_trick_frontPicture").val('');
        $("#snowtricks_appbundle_trick_frontPictureName").val('');
    });

    // partie vignette

    var $countPictures = $('[id^=addPicture]').length;
    if($countPictures>1)
        var cloneCount = $countPictures-1;
    else
        cloneCount = 0;



    function clone() {
        $('#addPicture')
            .clone(true)
            .attr('id', 'addPicture'+ cloneCount++)
            .insertAfter($('[id^=addPicture]:last'));
    }

    $('.deletePicture').on('click', function() {
        var $str = $(this).parent().parent().parent().parent().parent().attr('id');
        $id = $str.replace("addPicture", "");
        var $div = '#snowtricks_appbundle_trick_pictures_'+$id;
        $($div).parent().remove();
        $('#addPicture'+$id).remove()
    });

    $('.addPicture').click(function(){
        var $str = $(this).parent().parent().parent().parent().parent().attr('id');
        $id = $str.replace("addPicture", "");
        $('#snowtricks_appbundle_trick_pictures_'+$id+'_file').trigger('click');
        onChange($id);
    });


    function changePicture(input, $id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#addPicture'+$id).children('div').find('img').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function onChange($id) {
        $('#snowtricks_appbundle_trick_pictures_'+$id+'_file').change(function(){
            changePicture(this, $id);
        });
    }

    // partie video

    var $countVideos = $('[id^=addVideo]').length;

    if($countVideos>1)
        var cloneVideoCount = $countVideos-1;
    else
        cloneVideoCount = 0;

    function cloneVideo() {
        $('#addVideo')
            .clone(true)
            .attr('id', 'addVideo'+ cloneVideoCount)
            .insertAfter($('[id^=addVideo]:last'));
        $('#addVideo'+ cloneVideoCount).children('div').find('button').attr('data-target', 'modalVideo'+ cloneVideoCount);
        $('#addVideo'+ cloneVideoCount).children('div').find('button').attr('id', 'dataVideo'+ cloneVideoCount);
        $('#modalVideo')
            .clone(true)
            .attr('id', 'modalVideo'+ cloneVideoCount)
            .insertAfter($('[id^=modalVideo]:last'));
        cloneVideoCount++;
    }

    $('.deleteVideo').on('click', function() {
        var $str = $(this).parent().parent().parent().parent().parent().attr('id');
        $id = $str.replace("addVideo", "");
        var $div = '#snowtricks_appbundle_trick_videos_'+$id;
        $($div).parent().remove();
        $('#addVideo'+$id).remove();
    });

    $('.addVideo').click(function(){
        var $str = this.id;
        var $id = $str.replace("dataVideo", "");
        $('#modalVideo'+$id).modal('show');
    });

    $('.saveVideo').click(function () {
        var $str = $(this).parents('.modal').attr('id');
        var $id = $str.replace("modalVideo", "");
        saveVideo($id);
    });


    function saveVideo($id) {
        var $url = $('#modalVideo'+$id).find('input').val();
        $('#snowtricks_appbundle_trick_videos_'+$id+'_url').val($url);
        $('#modalVideo'+$id).modal('toggle');
        $('#addVideo'+$id).find('iframe').attr('src', $url);
    }

    // partie ajout dynamique form

    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#snowtricks_appbundle_trick_pictures');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var $index1 = $container.find('.form-control-file').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_picture').click(function(e) {
        addPicture($container);
        console.log($container);
        clone();
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if ($index1 === 0) {
        addPicture($container);
        clone();
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire
    function addPicture($container) {
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Photo N°' + ($index1+1))
            .replace(/__name__/g,        $index1)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        $index1++;
    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function(e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    //    Video ////////////////////////////////

    var $container2 = $('div#snowtricks_appbundle_trick_videos');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var $index = $container2.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#add_video').click(function(e) {
        addVideo($container2);
        cloneVideo();
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if ($index === 0) {
        addVideo($container2);
        cloneVideo();
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container2.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addVideo($container2) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container2.attr('data-prototype')
            .replace(/__name__label__/g, 'Video N°' + ($index+1))
            .replace(/__name__/g,        $index)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container2.append($prototype);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        $index++;
    }
});