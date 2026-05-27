<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->loginRouteSlug('login')
            ->authGuard('web')
            ->brandName('Garuda Admin')
            ->brandLogo(asset('assets/images/logos/logo.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('assets/images/logos/logo.svg'))
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->darkMode(false)
            ->renderHook(
                'panels::head.end',
                fn () => '
                <style>
                    /* Sidebar */
                    .fi-sidebar {
                        background: linear-gradient(180deg, #85C8FF 0%, #D4D1FE 100%) !important;
                        border-right: none !important;
                    }

                    .fi-sidebar-header {
                        background: transparent !important;
                        border-bottom: 1px solid rgba(255,255,255,0.4) !important;
                        padding: 1.25rem 1rem !important;
                    }

                    .fi-brand-name {
                        color: #0f172a !important;
                        font-weight: 700 !important;
                        font-size: 1.1rem !important;
                    }

                    .fi-sidebar-nav-groups {
                        padding: 0.5rem !important;
                    }

                    .fi-sidebar-item-button {
                        border-radius: 10px !important;
                        margin-bottom: 2px !important;
                        transition: all 0.2s !important;
                    }

                    .fi-sidebar-item-label {
                        color: #1e3a5f !important;
                        font-weight: 500 !important;
                    }

                    .fi-sidebar-item-icon {
                        color: #1e3a5f !important;
                    }

                    .fi-sidebar-item-button:hover {
                        background: rgba(255,255,255,0.35) !important;
                    }

                    .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                    .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        color: #0f172a !important;
                    }

                    .fi-sidebar-item-button.fi-active {
                        background: rgba(255,255,255,0.5) !important;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
                    }

                    .fi-sidebar-item-button.fi-active .fi-sidebar-item-label {
                        color: #0056b3 !important;
                        font-weight: 700 !important;
                    }

                    .fi-sidebar-item-button.fi-active .fi-sidebar-item-icon {
                        color: #0056b3 !important;
                    }

                    .fi-sidebar-group-label {
                        color: rgba(30,58,95,0.6) !important;
                        font-size: 0.7rem !important;
                        letter-spacing: 0.1em !important;
                    }

                    /* Main background */
                    .fi-main,
                    .fi-body,
                    body {
                        background: linear-gradient(135deg, #85C8FF 0%, #D4D1FE 47%, #F3F6FD 100%) !important;
                    }

                    /* Topbar */
                    .fi-topbar {
                        background: rgba(255,255,255,0.7) !important;
                        backdrop-filter: blur(10px) !important;
                        border-bottom: 1px solid rgba(255,255,255,0.5) !important;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
                    }

                    /* Page header */
                    .fi-header-heading {
                        font-size: 1.5rem !important;
                        font-weight: 700 !important;
                        color: #0f172a !important;
                    }

                    /* Cards/Widgets */
                    .fi-wi-stats-overview-stat,
                    .fi-wi-account,
                    .fi-wi-filament-info {
                        border-radius: 16px !important;
                        border: 1px solid rgba(255,255,255,0.8) !important;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.06) !important;
                        background: rgba(255,255,255,0.45) !important;
                        backdrop-filter: blur(10px) !important;
                    }

                    .fi-wi-stats-overview-stat-value {
                        font-size: 1.75rem !important;
                        font-weight: 700 !important;
                        color: #0056b3 !important;
                    }

                    .fi-wi-stats-overview-stat-label {
                        font-size: 0.75rem !important;
                        color: #64748b !important;
                        font-weight: 600 !important;
                        text-transform: uppercase !important;
                        letter-spacing: 0.05em !important;
                    }

                    /* Tables */
                    .fi-ta-table,
                    .fi-ta-ctn,
                    .fi-ta-header,
                    .fi-ta-footer {
                        background: rgba(255,255,255,0.35) !important;
                        backdrop-filter: blur(10px) !important;
                        border-radius: 16px !important;
                        border: 1px solid rgba(255,255,255,0.6) !important;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.06) !important;
                    }

                    .fi-ta-row {
                        background: transparent !important;
                    }

                    .fi-ta-row:hover td {
                        background: rgba(255,255,255,0.3) !important;
                    }

                    .fi-ta-header-cell {
                        background: rgba(255,255,255,0.2) !important;
                        font-weight: 600 !important;
                        color: #0056b3 !important;
                        font-size: 0.75rem !important;
                        text-transform: uppercase !important;
                        letter-spacing: 0.05em !important;
                    }

                    /* Buttons */
                    .fi-btn-primary {
                        border-radius: 8px !important;
                        font-weight: 600 !important;
                    }

                    /* Login page */
                    .fi-simple-layout {
                        background: linear-gradient(135deg, #85C8FF 0%, #D4D1FE 47%, #F3F6FD 100%) !important;
                    }

                    .fi-simple-main {
                        border-radius: 20px !important;
                        box-shadow: 0 25px 60px rgba(0,0,0,0.15) !important;
                        border: 1px solid rgba(255,255,255,0.6) !important;
                        background: rgba(255,255,255,0.75) !important;
                        backdrop-filter: blur(10px) !important;
                    }
                </style>
                '
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}