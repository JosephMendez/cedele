<?php
    $listTabs = [
        [
            'url' => 'cedele-setting',
            'title' => 'Collection Timeslot'
        ],
        [
            'url' => 'cut-off-time',
            'title' => 'Cut-off time',
        ],
        [
            'url' => 'shipping-time',
            'title' => 'Shipping time',
        ],
        [
            'url' => 'self-collection-inventory-management',
            'title' => 'Self-Collection Inventory Management',
        ],
        [
            'url' => 'home-setting',
            'title' => 'Home Screen Setting',
        ],
        [
            'url' => 'product-label',
            'title' => 'Product label',
        ],
        [
            'url' => 'manage-driver',
            'title' => 'Driver Management',
        ],
        [
            'url' => 'config-image',
            'title' => 'Media Management',
        ],
        [
            'url' => 'migrate-data',
            'title' => 'Migrate data',
        ],
        [
            'url' => 'send-email',
            'title' => 'Send email',
        ]
    ];
?>

<div class="cdls-nav">
    <?php
        $current_page = $_GET['page'];
        foreach ($listTabs as $key => $tab):
    ?>
    <div class="cdls-nav-item <?php echo $current_page === $tab['url'] ? 'active' : '' ?>">
        <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=' . $tab['url']);?>"><?php echo $tab['title']; ?></a>
    </div>
    <?php
        endforeach;
    ?>
</div>
