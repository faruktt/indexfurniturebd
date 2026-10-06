@extends('admin.layouts.app')

@section('title', 'Coupons & Discounts Management')
@section('page_title', 'Coupons & Discount Codes')

@section('content')
<div style="margin-bottom: 24px;">
    <!-- Stats Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div style="background: #ffffff; border: 1px solid var(--admin-card-border); border-radius: 8px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Coupons</div>
                <div style="font-size: 24px; font-weight: 700; color: #0f172a; margin-top: 4px;">{{ $totalCoupons }}</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>

        <div style="background: #ffffff; border: 1px solid var(--admin-card-border); border-radius: 8px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Active Coupons</div>
                <div style="font-size: 24px; font-weight: 700; color: #16a34a; margin-top: 4px;">{{ $activeCoupons }}</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div style="background: #ffffff; border: 1px solid var(--admin-card-border); border-radius: 8px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Redeemed</div>
                <div style="font-size: 24px; font-weight: 700; color: #8b5cf6; margin-top: 4px;">{{ $totalUses }}</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #f5f3ff; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-receipt"></i>
            </div>
        </div>

        <div style="background: #ffffff; border: 1px solid var(--admin-card-border); border-radius: 8px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11.5px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Discount Given</div>
                <div style="font-size: 22px; font-weight: 700; color: #ea580c; margin-top: 4px;">BDT {{ number_format($totalDiscountGiven, 0) }}</div>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #fff7ed; color: #ea580c; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-ticket-alt" style="color: var(--admin-primary);"></i>
                Coupon Codes List
            </h3>

            <!-- Search & Filters -->
            <form action="{{ route('admin.coupons.index') }}" method="GET" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #94a3b8;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code or note..." 
                        style="padding: 6px 10px 6px 28px; font-size: 12px; border: 1px solid var(--admin-card-border); border-radius: 6px; outline: none; width: 170px;">
                </div>

                <select name="type" onchange="this.form.submit()" style="padding: 6px 10px; font-size: 12px; border: 1px solid var(--admin-card-border); border-radius: 6px; outline: none; color: #475569;">
                    <option value="">All Types</option>
                    <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                </select>

                <select name="status" onchange="this.form.submit()" style="padding: 6px 10px; font-size: 12px; border: 1px solid var(--admin-card-border); border-radius: 6px; outline: none; color: #475569;">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline btn-sm" style="padding: 5px 8px; font-size: 11px;" title="Reset filters">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        <button type="button" class="btn btn-primary btn-sm" onclick="openAddCouponModal()">
            <i class="fas fa-plus"></i> Create New Coupon
        </button>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Coupon Code</th>
                    <th>Discount Type &amp; Value</th>
                    <th>Min Spend &amp; Cap</th>
                    <th>Usage / Limits</th>
                    <th>Per User Limit</th>
                    <th>Validity Period</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    @php
                        $isExpired = $coupon->end_date && \Carbon\Carbon::today()->gt($coupon->end_date);
                        $isNotStarted = $coupon->start_date && \Carbon\Carbon::today()->lt($coupon->start_date);
                        $isUsageFull = $coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit;
                    @endphp
                    <tr>
                        <!-- Coupon Code -->
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <div>
                                    <div style="font-family: monospace; font-size: 13px; font-weight: 700; color: #0f172a; letter-spacing: 0.05em; background: #f8fafc; border: 1px dashed #cbd5e1; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                        {{ $coupon->code }}
                                    </div>
                                    @if($coupon->description)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 3px; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $coupon->description }}">
                                            {{ $coupon->description }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Discount Type & Value -->
                        <td>
                            @if($coupon->type === 'percent')
                                <span class="badge" style="background: #fdf2f8; color: #db2777; font-weight: 700; font-size: 12px; padding: 4px 8px;">
                                    <i class="fas fa-percent" style="font-size: 10px; margin-right: 4px;"></i> {{ (float) $coupon->value }}% Off
                                </span>
                            @else
                                <span class="badge" style="background: #ecfdf5; color: #059669; font-weight: 700; font-size: 12px; padding: 4px 8px;">
                                    <i class="fas fa-money-bill-wave" style="font-size: 10px; margin-right: 4px;"></i> ৳{{ number_format($coupon->value, 0) }} Flat
                                </span>
                            @endif
                        </td>

                        <!-- Min Spend & Cap -->
                        <td>
                            <div style="font-size: 11.5px; color: #334155;">
                                <div><strong>Min Order:</strong> {{ $coupon->min_spend ? '৳' . number_format($coupon->min_spend, 0) : 'None' }}</div>
                                @if($coupon->type === 'percent' && $coupon->max_discount)
                                    <div style="color: #64748b;"><strong>Max Cap:</strong> ৳{{ number_format($coupon->max_discount, 0) }}</div>
                                @endif
                            </div>
                        </td>

                        <!-- Usage / Limits -->
                        <td>
                            <div style="font-size: 11.5px;">
                                <div style="font-weight: 600; color: {{ $isUsageFull ? '#dc2626' : '#0f172a' }};">
                                    Used: {{ $coupon->used_count }} 
                                    @if($coupon->usage_limit)
                                        / <span style="color: #64748b;">{{ $coupon->usage_limit }}</span>
                                    @else
                                        <span style="color: #10b981; font-weight: normal;">(Unlimited)</span>
                                    @endif
                                </div>
                                @if($coupon->usage_limit)
                                    @php
                                        $percentUsed = min(100, round(($coupon->used_count / $coupon->usage_limit) * 100));
                                    @endphp
                                    <div style="width: 100px; height: 5px; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-top: 4px;">
                                        <div style="width: {{ $percentUsed }}%; height: 100%; background: {{ $percentUsed >= 100 ? '#ef4444' : ($percentUsed > 75 ? '#f59e0b' : '#3b82f6') }};"></div>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Per User Limit -->
                        <td>
                            <div style="font-size: 12px; font-weight: 600; color: #334155;">
                                <i class="fas fa-user-check" style="color: #64748b; font-size: 11px; margin-right: 3px;"></i>
                                {{ $coupon->user_limit }}x per user
                            </div>
                        </td>

                        <!-- Validity Period -->
                        <td>
                            <div style="font-size: 11.5px;">
                                @if(!$coupon->start_date && !$coupon->end_date)
                                    <span style="color: #10b981; font-weight: 500;">Always Valid</span>
                                @else
                                    <div style="color: #475569;">
                                        {{ $coupon->start_date ? $coupon->start_date->format('M d, Y') : 'Start' }} 
                                        &rarr; 
                                        {{ $coupon->end_date ? $coupon->end_date->format('M d, Y') : 'No end' }}
                                    </div>
                                    @if($isExpired)
                                        <span class="badge badge-danger" style="font-size: 9.5px; padding: 1px 5px; margin-top: 2px;">Expired</span>
                                    @elseif($isNotStarted)
                                        <span class="badge badge-warning" style="font-size: 9.5px; padding: 1px 5px; margin-top: 2px;">Upcoming</span>
                                    @endif
                                @endif
                            </div>
                        </td>

                        <!-- Status Toggle -->
                        <td>
                            <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" title="Click to toggle status">
                                    @if($coupon->status)
                                        <span class="badge badge-success"><i class="fas fa-check-circle" style="font-size: 9px; margin-right: 3px;"></i> Active</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle" style="font-size: 9px; margin-right: 3px;"></i> Inactive</span>
                                    @endif
                                </button>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td style="text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 11px;"
                                    onclick='openEditCouponModal(@json($coupon))'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Delete this coupon code: {{ $coupon->code }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm" title="Delete Coupon">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 48px; color: #94a3b8;">
                            <i class="fas fa-ticket-alt" style="font-size: 36px; margin-bottom: 12px; display: block; opacity: 0.35;"></i>
                            <div style="font-size: 14px; font-weight: 600; color: #475569;">No coupons found</div>
                            <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Click "Create New Coupon" to set up your first promo discount code.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--admin-card-border);">
            {{ $coupons->links() }}
        </div>
    @endif
</div>

<!-- ================= ADD COUPON MODAL ================= -->
<div id="addCouponModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: #ffffff; width: 100%; max-width: 640px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); overflow: hidden; margin: 20px; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #f8fafc;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle" style="color: var(--admin-primary);"></i>
                Create New Coupon
            </h3>
            <button type="button" onclick="closeAddCouponModal()" style="border: none; background: transparent; font-size: 18px; color: #94a3b8; cursor: pointer; padding: 4px;">&times;</button>
        </div>

        <form action="{{ route('admin.coupons.store') }}" method="POST" style="padding: 24px; overflow-y: auto; flex: 1;">
            @csrf

            <!-- Code & Type -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Coupon Code <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="code" required placeholder="e.g. SUMMER20 or SAVE500" 
                        style="width: 100%; padding: 9px 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; font-family: monospace;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Will be converted to uppercase automatically.</div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Discount Type <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="type" id="addCouponType" required onchange="toggleTypeFields('add')"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                        <option value="percent">Percentage (%) Discount</option>
                        <option value="fixed">Fixed Amount (BDT Flat)</option>
                    </select>
                </div>
            </div>

            <!-- Value & Max Discount Cap -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        <span id="addValueLabel">Discount Percentage (%)</span> <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="number" step="0.01" min="0.01" name="value" required placeholder="e.g. 10 or 500"
                            style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                        <span id="addValueUnit" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 12px; color: #94a3b8; font-weight: 600;">%</span>
                    </div>
                </div>

                <div id="addMaxDiscountGroup">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Max Discount Cap (BDT)
                    </label>
                    <input type="number" step="0.01" min="0" name="max_discount" placeholder="e.g. 1000 (Optional)"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Limit maximum discount for percentage type.</div>
                </div>
            </div>

            <!-- Min Spend & Limits -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Minimum Spend (BDT)
                    </label>
                    <input type="number" step="0.01" min="0" name="min_spend" placeholder="e.g. 1500 (Optional)"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Order must reach this amount before discount.</div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Total Usage Limit (Limited User)
                    </label>
                    <input type="number" min="1" name="usage_limit" placeholder="e.g. 50 (Leave blank for unlimited)"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Total times this coupon can be used across entire store.</div>
                </div>
            </div>

            <!-- Per User Limit & Dates -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Usage Limit Per Single User <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" min="1" name="user_limit" value="1" required
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">How many times a single customer can use it (Default 1).</div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Start Date &rarr; End Date
                    </label>
                    <div style="display: flex; gap: 8px;">
                        <input type="date" name="start_date" placeholder="Start"
                            style="width: 50%; padding: 8px 10px; font-size: 12px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                        <input type="date" name="end_date" placeholder="End"
                            style="width: 50%; padding: 8px 10px; font-size: 12px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Leave blank if valid indefinitely.</div>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                    Description / Note (Optional)
                </label>
                <textarea name="description" rows="2" placeholder="e.g. Eid Mega Sale special 10% discount"
                    style="width: 100%; padding: 8px 12px; font-size: 12.5px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;"></textarea>
            </div>

            <!-- Status Checkbox -->
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                <input type="checkbox" id="addStatus" name="status" value="1" checked style="width: 16px; height: 16px; accent-color: var(--admin-primary); cursor: pointer;">
                <label for="addStatus" style="font-size: 13px; font-weight: 600; color: #1e293b; cursor: pointer;">
                    Active Coupon (Customers can immediately use this code)
                </label>
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" class="btn btn-outline" onclick="closeAddCouponModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Save &amp; Publish Coupon
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= EDIT COUPON MODAL ================= -->
<div id="editCouponModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: #ffffff; width: 100%; max-width: 640px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); overflow: hidden; margin: 20px; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #f8fafc;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit" style="color: var(--admin-primary);"></i>
                Edit Coupon: <span id="editModalCouponCodeDisplay" style="font-family: monospace; color: var(--admin-primary);"></span>
            </h3>
            <button type="button" onclick="closeEditCouponModal()" style="border: none; background: transparent; font-size: 18px; color: #94a3b8; cursor: pointer; padding: 4px;">&times;</button>
        </div>

        <form id="editCouponForm" method="POST" style="padding: 24px; overflow-y: auto; flex: 1;">
            @csrf
            @method('PUT')

            <!-- Code & Type -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Coupon Code <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="editCode" name="code" required 
                        style="width: 100%; padding: 9px 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; font-family: monospace;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Discount Type <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="type" id="editCouponType" required onchange="toggleTypeFields('edit')"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                        <option value="percent">Percentage (%) Discount</option>
                        <option value="fixed">Fixed Amount (BDT Flat)</option>
                    </select>
                </div>
            </div>

            <!-- Value & Max Discount Cap -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        <span id="editValueLabel">Discount Value</span> <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="number" step="0.01" min="0.01" id="editValue" name="value" required
                            style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                        <span id="editValueUnit" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 12px; color: #94a3b8; font-weight: 600;">%</span>
                    </div>
                </div>

                <div id="editMaxDiscountGroup">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Max Discount Cap (BDT)
                    </label>
                    <input type="number" step="0.01" min="0" id="editMaxDiscount" name="max_discount"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Limit maximum discount for percentage type.</div>
                </div>
            </div>

            <!-- Min Spend & Limits -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Minimum Spend (BDT)
                    </label>
                    <input type="number" step="0.01" min="0" id="editMinSpend" name="min_spend"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Order must reach this amount before discount.</div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Total Usage Limit (Limited User)
                    </label>
                    <input type="number" min="1" id="editUsageLimit" name="usage_limit"
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Total times this coupon can be used across entire store.</div>
                </div>
            </div>

            <!-- Per User Limit & Dates -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Usage Limit Per Single User <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" min="1" id="editUserLimit" name="user_limit" required
                        style="width: 100%; padding: 9px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">How many times a single customer can use it.</div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                        Start Date &rarr; End Date
                    </label>
                    <div style="display: flex; gap: 8px;">
                        <input type="date" id="editStartDate" name="start_date"
                            style="width: 50%; padding: 8px 10px; font-size: 12px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                        <input type="date" id="editEndDate" name="end_date"
                            style="width: 50%; padding: 8px 10px; font-size: 12px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                    Description / Note (Optional)
                </label>
                <textarea id="editDescription" name="description" rows="2"
                    style="width: 100%; padding: 8px 12px; font-size: 12.5px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;"></textarea>
            </div>

            <!-- Status Checkbox -->
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                <input type="checkbox" id="editStatus" name="status" value="1" style="width: 16px; height: 16px; accent-color: var(--admin-primary); cursor: pointer;">
                <label for="editStatus" style="font-size: 13px; font-weight: 600; color: #1e293b; cursor: pointer;">
                    Active Coupon (Customers can immediately use this code)
                </label>
            </div>

            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" class="btn btn-outline" onclick="closeEditCouponModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Coupon
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTypeFields(mode) {
    const typeSelect = document.getElementById(mode + 'CouponType');
    const valueLabel = document.getElementById(mode + 'ValueLabel');
    const valueUnit = document.getElementById(mode + 'ValueUnit');
    const maxDiscountGroup = document.getElementById(mode + 'MaxDiscountGroup');

    if (typeSelect.value === 'percent') {
        valueLabel.innerText = 'Discount Percentage (%)';
        valueUnit.innerText = '%';
        if (maxDiscountGroup) maxDiscountGroup.style.display = 'block';
    } else {
        valueLabel.innerText = 'Flat Discount (BDT)';
        valueUnit.innerText = 'BDT';
        if (maxDiscountGroup) maxDiscountGroup.style.display = 'none';
    }
}

function openAddCouponModal() {
    toggleTypeFields('add');
    const modal = document.getElementById('addCouponModal');
    modal.style.display = 'flex';
}

function closeAddCouponModal() {
    document.getElementById('addCouponModal').style.display = 'none';
}

function openEditCouponModal(coupon) {
    const form = document.getElementById('editCouponForm');
    form.action = '/admin/coupons/' + coupon.id;

    document.getElementById('editModalCouponCodeDisplay').innerText = coupon.code;
    document.getElementById('editCode').value = coupon.code;
    document.getElementById('editCouponType').value = coupon.type;
    document.getElementById('editValue').value = coupon.value;
    document.getElementById('editMaxDiscount').value = coupon.max_discount || '';
    document.getElementById('editMinSpend').value = coupon.min_spend || '';
    document.getElementById('editUsageLimit').value = coupon.usage_limit || '';
    document.getElementById('editUserLimit').value = coupon.user_limit || 1;
    document.getElementById('editStartDate').value = coupon.start_date ? coupon.start_date.substring(0, 10) : '';
    document.getElementById('editEndDate').value = coupon.end_date ? coupon.end_date.substring(0, 10) : '';
    document.getElementById('editDescription').value = coupon.description || '';
    document.getElementById('editStatus').checked = Boolean(coupon.status);

    toggleTypeFields('edit');

    document.getElementById('editCouponModal').style.display = 'flex';
}

function closeEditCouponModal() {
    document.getElementById('editCouponModal').style.display = 'none';
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    const addModal = document.getElementById('addCouponModal');
    const editModal = document.getElementById('editCouponModal');
    if (e.target === addModal) addModal.style.display = 'none';
    if (e.target === editModal) editModal.style.display = 'none';
});
</script>
@endsection
