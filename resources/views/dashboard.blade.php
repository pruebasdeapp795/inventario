@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .metric-card.dark {
            background: var(--sidebar-bg);
            color: white;
        }

        .metric-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .metric-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .metric-card.dark .metric-icon {
            background: rgba(255, 255, 255, 0.1);
            color: var(--accent-color);
        }

        .metric-card:not(.dark) .metric-icon {
            background: #f7f8fa;
            color: var(--text-main);
        }

        .metric-action {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            text-decoration: none;
        }

        .metric-card.dark .metric-action {
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .metric-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .metric-card.dark .metric-label {
            color: #a4a8b2;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .metric-bottom {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
        }

        .badge-trend {
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .badge-trend.positive {
            background: rgba(46, 214, 163, 0.1);
            color: #2ed6a3;
        }

        .badge-trend.negative {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
        }

        /* Sections */
        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .panel {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: 600;
        }
    </style>

    <div class="metrics-grid">
        <div class="metric-card dark">
            <div class="metric-top">
                <div class="metric-icon"><i class='bx bx-cube'></i></div>
                <a href="#" class="metric-action"><i class='bx bx-up-arrow-alt' style="transform: rotate(45deg);"></i></a>
            </div>
            <div class="metric-label"></div>
            <div class="metric-value"></div>
            <div class="metric-bottom">
                <span class="badge-trend positive"> <i class='bx bx-trending-up'></i></span>
                <span style="color: #a4a8b2"></span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon" style="color: #2ed6a3; background: rgba(46,214,163,0.1);"><i
                        class='bx bx-dollar'></i></div>
                <a href="#" class="metric-action"><i class='bx bx-up-arrow-alt' style="transform: rotate(45deg);"></i></a>
            </div>
            <div class="metric-label"></div>
            <div class="metric-value"></div>
            <div class="metric-bottom">
                <span class="badge-trend positive"> <i class='bx bx-trending-up'></i></span>
                <span class="text-muted" style="color: var(--text-muted)"></span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon" style="color: var(--accent-color); background: rgba(220,247,66,0.2);"><i
                        class='bx bx-check-circle' style="color: #bcce2b;"></i></div>
                <a href="#" class="metric-action"><i class='bx bx-up-arrow-alt' style="transform: rotate(45deg);"></i></a>
            </div>
            <div class="metric-label"></div>
            <div class="metric-value"></div>
            <div style="height: 6px; background: #f0f0f0; border-radius: 3px; margin: 12px 0; overflow:hidden;">
                <div style="width: 90%; background: linear-gradient(90deg, #dcf742, #2ed6a3); height: 100%;"></div>
            </div>
            <div class="metric-bottom">
                <span class="badge-trend positive"> <i class='bx bx-trending-up'></i></span>
                <span class="text-muted" style="color: var(--text-muted)"></span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon"><i class='bx bx-time'></i></div>
                <a href="#" class="metric-action"><i class='bx bx-up-arrow-alt' style="transform: rotate(45deg);"></i></a>
            </div>
            <div class="metric-label"></div>
            <div class="metric-value"></div>
            <div class="metric-bottom">
                <span class="badge-trend positive"> <i class='bx bx-trending-up'></i></span>
                <span class="text-muted" style="color: var(--text-muted)"></span>
            </div>
        </div>
    </div>

    <div class="dashboard-row">
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title"></div>
                <div style="display: flex; gap: 8px;">
                    <button
                        style="border: 1px solid var(--border-color); background: white; border-radius: 8px; padding: 6px 12px; font-size: 13px;"><i
                            class='bx bx-calendar'></i> Monthly <i class='bx bx-chevron-down'></i></button>
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--border-color);">
                        <th style="padding-bottom: 12px; font-weight: 500;"></th>
                        <th style="padding-bottom: 12px; font-weight: 500;"></th>
                        <th style="padding-bottom: 12px; font-weight: 500;"></th>
                        <th style="padding-bottom: 12px; font-weight: 500;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 16px 0; display: flex; align-items: center; gap: 12px;">
                            <img src=""
                                style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                            <div>
                                <div style="font-weight: 500;"></div>
                                <div style="font-size: 12px; color: var(--text-muted);"></div>
                            </div>
                        </td>
                        <td style="padding: 16px 0;"></td>
                        <td style="padding: 16px 0; font-weight: 500;"></td>
                        <td style="padding: 16px 0;"><span style="color: #2ed6a3; font-weight: 500;"></span></td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-color);">
                        <td style="padding: 16px 0; display: flex; align-items: center; gap: 12px;">
                            <img src=""
                                style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                            <div>
                                <div style="font-weight: 500;"></div>
                                <div style="font-size: 12px; color: var(--text-muted);"></div>
                            </div>
                        </td>
                        <td style="padding: 16px 0;"></td>
                        <td style="padding: 16px 0; font-weight: 500;"></td>
                        <td style="padding: 16px 0;"><span style="color: #f1c40f; font-weight: 500;">In Progress</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div class="panel-title"></div>
            </div>
            <div style="background: #f7f8fa; border-radius: 12px; padding: 20px; text-align: center;">
                <img src=""
                    style="width: 100%; height: 140px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
                <div style="font-weight: 600; font-size: 16px; margin-bottom: 4px;"></div>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;"></div>
                <button
                    style="width: 100%; background: var(--sidebar-bg); color: white; border: none; padding: 10px; border-radius: 8px; font-weight: 500; cursor: pointer;"></button>
            </div>
        </div>
    </div>
@endsection