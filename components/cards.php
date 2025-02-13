<?php
function generateCard($title, $content, $image = null, $link = null)
{
?>
    <div class="card mb-4">
        <?php if ($image): ?>
            <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo $title; ?>">
        <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title"><?php echo $title; ?></h5>
            <p class="card-text"><?php echo $content; ?></p>
            <?php if ($link): ?>
                <a href="<?php echo $link; ?>" class="btn btn-primary">Selengkapnya</a>
            <?php endif; ?>
        </div>
    </div>
<?php
}

function generateInfoCard($icon, $title, $value)
{
?>
    <div class="card info-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="<?php echo $icon; ?>"></i>
                </div>
                <div class="ps-3">
                    <h6><?php echo $title; ?></h6>
                    <h4><?php echo $value; ?></h4>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>