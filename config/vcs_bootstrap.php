<?php
/**
 * Routes
 *
 * example_routes.php will be loaded in main app/config/routes.php file.
 */
//    Croogo::hookRoutes('Video');
/**
 * Behavior
 *
 * This plugin's Example behavior will be attached whenever Node model is loaded.
 */
    Croogo::hookBehavior('Block', 'Vcs.Vcs');
    Croogo::hookBehavior('Link', 'Vcs.Vcs');
    Croogo::hookBehavior('Menu', 'Vcs.Vcs');
    Croogo::hookBehavior('Node', 'Vcs.Vcs');
    Croogo::hookBehavior('Region', 'Vcs.Vcs');
/**
 * Component
 *
 * This plugin's Example component will be loaded in ALL controllers.
 */
    Croogo::hookComponent('Blocks', 'Vcs.Vcs');
    Croogo::hookComponent('Links', 'Vcs.Vcs');
    Croogo::hookComponent('Menus', 'Vcs.Vcs');
    Croogo::hookComponent('Nodes', 'Vcs.Vcs');
    Croogo::hookComponent('Regions', 'Vcs.Vcs');
/**
 * Helper
 *
 * This plugin's Example helper will be loaded via NodesController.
 */
//    Croogo::hookHelper('Nodes', 'Example.Example');
/**
 * Admin menu (navigation)
 *
 * This plugin's admin_menu element will be rendered in admin panel under Extensions menu.
 */
    Croogo::hookAdminMenu('vcs');
/**
 * Admin row action
 *
 * When browsing the content list in admin panel (Content > List),
 * an extra link called 'Example' will be placed under 'Actions' column.
 */
    Croogo::hookAdminRowAction('Blocks/admin_index', 'Revision', 'plugin:vcs/controller:vcs_revisions/action:index/Block/:id');
    Croogo::hookAdminRowAction('Links/admin_index', 'Revision', 'plugin:vcs/controller:vcs_revisions/action:index/Link/:id');
    Croogo::hookAdminRowAction('Menus/admin_index', 'Revision', 'plugin:vcs/controller:vcs_revisions/action:index/Menu/:id');
    Croogo::hookAdminRowAction('Nodes/admin_index', 'Revision', 'plugin:vcs/controller:vcs_revisions/action:index/Node/:id');
    Croogo::hookAdminRowAction('Regions/admin_index', 'Revision', 'plugin:vcs/controller:vcs_revisions/action:index/Region/:id');
/**
 * Admin tab
 *
 * When adding/editing Content (Nodes),
 * an extra tab with title 'Example' will be shown with markup generated from the plugin's admin_tab_node element.
 *
 * Useful for adding form extra form fields if necessary.
 */
//    Croogo::hookAdminTab('Nodes/admin_add', 'Example', 'example.admin_tab_node');
    Croogo::hookAdminTab('Blocks/admin_edit', 'Revision', 'vcs.admin_tab_rev');
    Croogo::hookAdminTab('Links/admin_edit', 'Revision', 'vcs.admin_tab_rev');
    Croogo::hookAdminTab('Menus/admin_edit', 'Revision', 'vcs.admin_tab_rev');
    Croogo::hookAdminTab('Nodes/admin_edit', 'Revision', 'vcs.admin_tab_rev');
    Croogo::hookAdminTab('Regions/admin_edit', 'Revision', 'vcs.admin_tab_rev');
?>