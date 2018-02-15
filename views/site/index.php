<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Главная';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Центр Зайнятості</h1>

        <p class="lead">Ласкаво просимо до Центру!</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <?php echo Html::img(Url::to('/web/img/note.png')); ?>
            </div>
            <div class="col-lg-6">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel consectetur quam, eget dictum odio.
                Fusce eu ultrices est. Maecenas eget bibendum felis, id venenatis nulla. Nunc lobortis rutrum lorem et consequat.
                Vivamus bibendum vel sem quis ullamcorper. Pellentesque aliquam tellus a neque tempus, non scelerisque nisl aliquet.
                Ut aliquam viverra mi et interdum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
                Donec maximus feugiat consequat. Integer placerat neque vel metus lobortis molestie. Nunc congue pellentesque mi eu placerat.
                    Pellentesque sit amet nisl nulla.</p>

                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                Proin malesuada lacinia aliquam. Integer dignissim tellus ac porttitor congue.
                Mauris mattis, sapien volutpat imperdiet elementum, libero nunc dignissim dolor, ac faucibus enim orci ac velit.
                Donec sollicitudin a nunc sed tincidunt. Nam vitae lacus a mauris ornare porttitor eget eget nibh.
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin finibus commodo tortor, ut molestie magna ornare sed.</p>

                <p>Aliquam dictum eget ipsum at tincidunt. Pellentesque eleifend posuere vestibulum.
                Donec tempor, nisl eu tincidunt blandit, ligula purus lobortis metus, ut venenatis diam libero at ligula.
                Nam ultricies quis nibh ut egestas. Quisque non diam ac dolor accumsan commodo.
                Sed egestas consequat ipsum, quis cursus tellus consectetur at.
                Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
                Aenean ullamcorper mollis sapien, quis fermentum ligula fermentum non. Integer scelerisque auctor lectus at scelerisque.
                    Donec et finibus neque.</p>
            </div>
        </div>

    </div>
</div>
