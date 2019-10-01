/**
  * {@inheritdoc}
  */
 public function build()
 {
     // Get block
     $block = $this->getBlock();
     // Apply block config.
     $block_config = $this->blockConfig();
     $block->setConfiguration($block_config);
     // Get render array.
     $block_elements = $block->build();
     // Return an empty array if there is nothing to render.
     return Element::isEmpty($block_elements) ? [] : $block_elements;
 }