<div class="selected-sections">
  <?php foreach ($project->sections_by_category() as $category => $sections): ?>
    <h4><?php echo Jade\Dumper::_text($category); ?></h4>
    <?php foreach ($sections as $section): ?>
      <div class="section" data-section-id="<?php echo Jade\Dumper::_text($section->id); ?>">
        &bullet; <?php echo Jade\Dumper::_text($section->title); ?>
        <a class="btn btn-mini remove-button" data-href="<?php echo Jade\Dumper::_text(route('project_section_delete', array($project->id, $section->id))); ?>" data-loading-text="Removing...">
          <i class="icon-trash"></i>
        </a>
      </div>
    <?php endforeach; ?>
  <?php endforeach; ?>
</div>