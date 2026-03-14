@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    #designer-container { position: relative; }
    #konva-container canvas { border-radius: 0.5rem; }
    .toolbox-btn.active { background-color: #3B82F6; color: white; }
    .properties-panel input, .properties-panel select {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        width: 100%;
    }
    .properties-panel input:focus, .properties-panel select:focus {
        outline: none;
        border-color: #3B82F6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50" x-data="yardDesigner()">
    {{-- Top Toolbar --}}
    <div class="bg-white border-b shadow-sm px-4 py-2 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.yards.show', $yard) }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Yard Designer</h1>
            <span class="text-sm text-gray-500">{{ $yard->name }}</span>
        </div>

        <div class="flex items-center gap-2">
            <button @click="zoomIn()" class="p-2 hover:bg-gray-100 rounded-lg text-gray-600" title="Zoom In">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
            </button>
            <button @click="zoomOut()" class="p-2 hover:bg-gray-100 rounded-lg text-gray-600" title="Zoom Out">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM7 10h6"/></svg>
            </button>
            <button @click="resetZoom()" class="p-2 hover:bg-gray-100 rounded-lg text-gray-600" title="Reset View">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
            <div class="w-px h-6 bg-gray-300 mx-1"></div>
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                <input type="checkbox" x-model="snapToGrid" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                Grid Snap
            </label>
            <div class="w-px h-6 bg-gray-300 mx-1"></div>
            <button @click="saveLayout()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm" :disabled="saving">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span x-text="saving ? 'Saving...' : 'Save Layout'"></span>
            </button>
        </div>
    </div>

    <div class="flex" style="height: calc(100vh - 120px);">
        {{-- Left Sidebar - Toolbox --}}
        <div class="w-56 bg-white border-r shadow-sm p-4 overflow-y-auto">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tools</h3>
            <div class="space-y-1">
                <button @click="setTool('select')" :class="currentTool === 'select' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                    Select
                </button>
                <button @click="setTool('zone')" :class="currentTool === 'zone' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/></svg>
                    Add Zone
                </button>
                <button @click="setTool('slot')" :class="currentTool === 'slot' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100'" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="1" stroke-width="2"/></svg>
                    Add Slot
                </button>
                <button @click="deleteSelected()" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete Selected
                </button>
            </div>

            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-3">Zone Types</h3>
            <div class="space-y-2">
                <template x-for="zt in zoneTypes" :key="zt.value">
                    <button @click="selectedZoneType = zt.value; setTool('zone')" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium hover:bg-gray-100 transition-colors border" :class="selectedZoneType === zt.value ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                        <span class="w-3 h-3 rounded-full" :style="'background:' + zt.color"></span>
                        <span x-text="zt.label"></span>
                    </button>
                </template>
            </div>

            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-3">Zones (<span x-text="zones.length"></span>)</h3>
            <div class="space-y-1 max-h-48 overflow-y-auto">
                <template x-for="zone in zones" :key="zone.id || zone._tempId">
                    <div @click="selectZone(zone)" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs cursor-pointer hover:bg-gray-100 transition-colors" :class="selectedElement?.id === zone.id ? 'bg-blue-50 border border-blue-300' : ''">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" :style="'background:' + (zone.color || '#3B82F6')"></span>
                        <span class="truncate" x-text="zone.name || 'Unnamed Zone'"></span>
                        <span class="ml-auto text-gray-400" x-text="(zone.slots || []).length + 's'"></span>
                    </div>
                </template>
                <p x-show="zones.length === 0" class="text-xs text-gray-400 px-3 py-2">No zones yet. Use Add Zone tool.</p>
            </div>
        </div>

        {{-- Canvas Area --}}
        <div class="flex-1 bg-gray-100 relative overflow-hidden" id="designer-container">
            <div id="konva-container" class="w-full h-full"></div>

            {{-- Status bar --}}
            <div class="absolute bottom-0 left-0 right-0 bg-white/90 backdrop-blur border-t px-4 py-1.5 flex items-center gap-4 text-xs text-gray-500">
                <span>Zoom: <span x-text="Math.round(zoomLevel * 100)"></span>%</span>
                <span>Zones: <span x-text="zones.length"></span></span>
                <span>Total Slots: <span x-text="totalSlots"></span></span>
                <span x-show="selectedElement">Selected: <span x-text="selectedElement?.name || selectedElement?.slot_number || 'element'" class="font-medium text-blue-600"></span></span>
            </div>
        </div>

        {{-- Right Sidebar - Properties --}}
        <div class="w-72 bg-white border-l shadow-sm p-4 overflow-y-auto properties-panel">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Properties</h3>

            {{-- No selection --}}
            <div x-show="!selectedElement" class="text-center py-8">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2z"/></svg>
                <p class="text-sm text-gray-400">Select a zone or slot to view properties</p>
            </div>

            {{-- Zone Properties --}}
            <div x-show="selectedElement && selectedElement._type === 'zone'" class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Zone Name</label>
                    <input type="text" x-model="selectedElement.name" @input="updateElement()" placeholder="e.g., Loading Dock A">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                    <select x-model="selectedElement.type" @change="updateZoneColor(); updateElement()">
                        <template x-for="zt in zoneTypes" :key="zt.value">
                            <option :value="zt.value" x-text="zt.label"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Capacity</label>
                    <input type="number" x-model.number="selectedElement.capacity" @input="updateElement()" min="1">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Color</label>
                    <input type="color" x-model="selectedElement.color" @input="updateElement()" class="h-10 cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Priority</label>
                    <input type="number" x-model.number="selectedElement.priority" @input="updateElement()" min="0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                    <select x-model="selectedElement.status" @change="updateElement()">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="pt-2 border-t">
                    <p class="text-xs text-gray-500 mb-2">Slots in this zone: <span x-text="(selectedElement.slots || []).length" class="font-medium"></span></p>
                    <button @click="setTool('slot'); targetZone = selectedElement" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs py-2 rounded-lg font-medium transition-colors">
                        + Add Slot to This Zone
                    </button>
                </div>
            </div>

            {{-- Slot Properties --}}
            <div x-show="selectedElement && selectedElement._type === 'slot'" class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Slot Number</label>
                    <input type="text" x-model="selectedElement.slot_number" @input="updateElement()" placeholder="e.g., A-01">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                    <select x-model="selectedElement.type" @change="updateElement()">
                        <option value="truck_parking">Truck Parking</option>
                        <option value="dock">Dock</option>
                        <option value="staging">Staging</option>
                        <option value="waiting">Waiting</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Size</label>
                    <select x-model="selectedElement.size" @change="updateElement()">
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                        <option value="oversized">Oversized</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                    <select x-model="selectedElement.status" @change="updateElement()">
                        <option value="available">Available</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Features</label>
                    <div class="space-y-1">
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" x-model="selectedElement._features.refrigerated" @change="updateElement()" class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600">
                            Refrigerated
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" x-model="selectedElement._features.hazmat" @change="updateElement()" class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600">
                            Hazmat
                        </label>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" x-model="selectedElement._features.covered" @change="updateElement()" class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600">
                            Covered
                        </label>
                    </div>
                </div>
                <div class="pt-2 border-t">
                    <p class="text-xs text-gray-500">Zone: <span x-text="getSlotZoneName(selectedElement)" class="font-medium"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Konva.js CDN --}}
<script src="https://unpkg.com/konva@9/konva.min.js"></script>

<script>
function yardDesigner() {
    return {
        currentTool: 'select',
        selectedElement: null,
        selectedZoneType: 'parking',
        targetZone: null,
        snapToGrid: true,
        saving: false,
        zoomLevel: 1,
        zones: [],
        stage: null,
        layer: null,
        gridLayer: null,
        _tempIdCounter: 0,

        zoneTypes: [
            { value: 'parking', label: 'Parking', color: '#3B82F6' },
            { value: 'loading_dock', label: 'Loading Dock', color: '#8B5CF6' },
            { value: 'staging', label: 'Staging', color: '#F59E0B' },
            { value: 'waiting', label: 'Waiting', color: '#6B7280' },
            { value: 'maintenance', label: 'Maintenance', color: '#EF4444' },
        ],

        get totalSlots() {
            return this.zones.reduce((sum, z) => sum + (z.slots || []).length, 0);
        },

        init() {
            this.$nextTick(() => {
                const container = document.getElementById('konva-container');
                this.stage = new Konva.Stage({
                    container: 'konva-container',
                    width: container.offsetWidth,
                    height: container.offsetHeight,
                    draggable: true,
                });

                this.gridLayer = new Konva.Layer();
                this.layer = new Konva.Layer();
                this.stage.add(this.gridLayer);
                this.stage.add(this.layer);

                // Make grid lines non-interactive so clicks pass through
                this.gridLayer.listening(false);

                this.drawGrid();
                this.loadExistingLayout();

                // Track if mouse moved (to distinguish click from drag)
                let mouseDownPos = null;
                container.addEventListener('mousedown', (evt) => {
                    mouseDownPos = { x: evt.clientX, y: evt.clientY };
                });

                container.addEventListener('mouseup', (evt) => {
                    if (!mouseDownPos) return;
                    const dx = evt.clientX - mouseDownPos.x;
                    const dy = evt.clientY - mouseDownPos.y;
                    const wasDrag = Math.abs(dx) > 5 || Math.abs(dy) > 5;
                    mouseDownPos = null;

                    if (wasDrag) return; // was a drag, not a click

                    // Calculate pointer position relative to the container from DOM event
                    const rect = container.getBoundingClientRect();
                    const pointerPos = {
                        x: evt.clientX - rect.left,
                        y: evt.clientY - rect.top,
                    };

                    // Check if click was on a Konva shape (zone/slot handles its own click)
                    const shape = this.stage.getIntersection(pointerPos);
                    if (shape) return; // clicked on a shape, let Konva handle it

                    if (this.currentTool === 'zone') {
                        this.createZoneAt(pointerPos);
                    } else if (this.currentTool === 'select') {
                        this.deselectAll();
                    }
                });

                // Zoom
                this.stage.on('wheel', (e) => {
                    e.evt.preventDefault();
                    const scaleBy = 1.05;
                    const oldScale = this.stage.scaleX();
                    const pointer = this.stage.getPointerPosition();
                    const newScale = e.evt.deltaY > 0 ? oldScale / scaleBy : oldScale * scaleBy;

                    this.zoomLevel = Math.min(Math.max(newScale, 0.2), 5);
                    this.stage.scale({ x: this.zoomLevel, y: this.zoomLevel });

                    const mousePointTo = {
                        x: (pointer.x - this.stage.x()) / oldScale,
                        y: (pointer.y - this.stage.y()) / oldScale,
                    };
                    const newPos = {
                        x: pointer.x - mousePointTo.x * this.zoomLevel,
                        y: pointer.y - mousePointTo.y * this.zoomLevel,
                    };
                    this.stage.position(newPos);
                });

                // Resize
                window.addEventListener('resize', () => {
                    this.stage.width(container.offsetWidth);
                    this.stage.height(container.offsetHeight);
                    this.drawGrid();
                });
            });
        },

        drawGrid() {
            this.gridLayer.destroyChildren();
            const gridSize = 20;
            const width = 3000;
            const height = 2000;

            for (let i = 0; i <= width / gridSize; i++) {
                this.gridLayer.add(new Konva.Line({
                    points: [i * gridSize, 0, i * gridSize, height],
                    stroke: '#E5E7EB',
                    strokeWidth: 0.5,
                }));
            }
            for (let j = 0; j <= height / gridSize; j++) {
                this.gridLayer.add(new Konva.Line({
                    points: [0, j * gridSize, width, j * gridSize],
                    stroke: '#E5E7EB',
                    strokeWidth: 0.5,
                }));
            }
        },

        snapPosition(pos) {
            if (!this.snapToGrid) return pos;
            const gridSize = 20;
            return {
                x: Math.round(pos.x / gridSize) * gridSize,
                y: Math.round(pos.y / gridSize) * gridSize,
            };
        },

        setTool(tool) {
            this.currentTool = tool;
            this.stage.draggable(tool === 'select');
        },

        getZoneColor(type) {
            const zt = this.zoneTypes.find(z => z.value === type);
            return zt ? zt.color : '#3B82F6';
        },

        loadExistingLayout() {
            const existingZones = @json($designerZones);

            existingZones.forEach(zone => {
                zone._type = 'zone';
                zone.slots = (zone.slots || []).map(slot => {
                    slot._type = 'slot';
                    slot._features = slot.features || { refrigerated: false, hazmat: false, covered: false };
                    return slot;
                });
                this.zones.push(zone);
                this.renderZone(zone);
            });
        },

        createZoneAt(pointerPos) {
            // Convert screen pointer position to stage-relative coordinates (accounting for pan/zoom)
            const stagePos = this.stage.position();
            const scale = this.stage.scaleX();
            const relativePos = {
                x: (pointerPos.x - stagePos.x) / scale,
                y: (pointerPos.y - stagePos.y) / scale,
            };
            const pos = this.snapPosition(relativePos);
            const color = this.getZoneColor(this.selectedZoneType);
            const tempId = '_new_' + (++this._tempIdCounter);

            const zone = {
                _tempId: tempId,
                _type: 'zone',
                name: this.selectedZoneType.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) + ' ' + (this.zones.length + 1),
                type: this.selectedZoneType,
                capacity: 10,
                color: color,
                priority: this.zones.length,
                status: 'active',
                position_data: { x: pos.x, y: pos.y, width: 300, height: 200 },
                slots: [],
            };

            this.zones.push(zone);
            this.renderZone(zone);
            this.selectElement(zone);
        },

        renderZone(zone) {
            const pd = zone.position_data || { x: 50, y: 50, width: 300, height: 200 };
            const group = new Konva.Group({
                x: pd.x,
                y: pd.y,
                draggable: true,
                id: 'zone-' + (zone.id || zone._tempId),
            });

            const rect = new Konva.Rect({
                width: pd.width,
                height: pd.height,
                fill: (zone.color || '#3B82F6') + '20',
                stroke: zone.color || '#3B82F6',
                strokeWidth: 2,
                cornerRadius: 8,
                name: 'zone-rect',
            });

            const label = new Konva.Text({
                text: zone.name || 'Zone',
                x: 10,
                y: 8,
                fontSize: 14,
                fontStyle: 'bold',
                fill: zone.color || '#3B82F6',
                name: 'zone-label',
            });

            const typeLabel = new Konva.Text({
                text: (zone.type || '').replace('_', ' '),
                x: 10,
                y: 26,
                fontSize: 10,
                fill: '#9CA3AF',
                name: 'zone-type-label',
            });

            group.add(rect, label, typeLabel);

            // Click handler
            group.on('click tap', (e) => {
                e.cancelBubble = true;
                if (this.currentTool === 'slot') {
                    this.createSlot(zone, group, e);
                } else {
                    this.selectElement(zone);
                }
            });

            // Drag handler
            group.on('dragend', () => {
                const snapped = this.snapPosition(group.position());
                group.position(snapped);
                zone.position_data = {
                    ...zone.position_data,
                    x: snapped.x,
                    y: snapped.y,
                };
            });

            this.layer.add(group);

            // Render existing slots
            (zone.slots || []).forEach(slot => {
                this.renderSlot(slot, zone, group);
            });

            this.layer.draw();
        },

        createSlot(zone, group, e) {
            const pointerPos = this.stage.getRelativePointerPosition();
            const groupPos = group.position();
            const localPos = this.snapPosition({
                x: pointerPos.x - groupPos.x,
                y: pointerPos.y - groupPos.y,
            });

            const tempId = '_slot_' + (++this._tempIdCounter);
            const slotCount = (zone.slots || []).length;
            const prefix = zone.type === 'loading_dock' ? 'DOCK' : zone.name.substring(0, 2).toUpperCase();

            const slot = {
                _tempId: tempId,
                _type: 'slot',
                slot_number: prefix + '-' + String(slotCount + 1).padStart(2, '0'),
                type: zone.type === 'loading_dock' ? 'dock' : 'truck_parking',
                size: 'medium',
                status: 'available',
                position_data: { x: localPos.x, y: localPos.y, width: 50, height: 50 },
                features: { refrigerated: false, hazmat: false, covered: false },
                _features: { refrigerated: false, hazmat: false, covered: false },
                yard_zone_id: zone.id || null,
            };

            if (!zone.slots) zone.slots = [];
            zone.slots.push(slot);

            this.renderSlot(slot, zone, group);
            this.selectElement(slot);
        },

        renderSlot(slot, zone, group) {
            const pd = slot.position_data || { x: 60, y: 60, width: 50, height: 50 };

            const statusColorMap = {
                available: '#22C55E',
                occupied: '#EF4444',
                reserved: '#F59E0B',
                maintenance: '#6B7280',
                blocked: '#1F2937',
            };
            const fillColor = statusColorMap[slot.status] || '#22C55E';

            const slotGroup = new Konva.Group({
                x: pd.x,
                y: pd.y,
                draggable: true,
                id: 'slot-' + (slot.id || slot._tempId),
            });

            const slotRect = new Konva.Rect({
                width: pd.width,
                height: pd.height,
                fill: fillColor,
                stroke: '#FFFFFF',
                strokeWidth: 2,
                cornerRadius: 4,
                name: 'slot-rect',
                shadowColor: '#000',
                shadowBlur: 4,
                shadowOpacity: 0.1,
                shadowOffset: { x: 1, y: 1 },
            });

            const textColor = ['blocked', 'maintenance', 'occupied', 'reserved'].includes(slot.status) ? '#FFFFFF' : '#1F2937';
            const slotLabel = new Konva.Text({
                text: slot.slot_number || '?',
                width: pd.width,
                height: pd.height,
                align: 'center',
                verticalAlign: 'middle',
                fontSize: 10,
                fontStyle: 'bold',
                fill: textColor,
                name: 'slot-label',
            });

            slotGroup.add(slotRect, slotLabel);

            slotGroup.on('click tap', (e) => {
                e.cancelBubble = true;
                this.selectElement(slot);
            });

            slotGroup.on('dragend', () => {
                const snapped = this.snapPosition(slotGroup.position());
                slotGroup.position(snapped);
                slot.position_data = {
                    ...slot.position_data,
                    x: snapped.x,
                    y: snapped.y,
                };
            });

            group.add(slotGroup);
            this.layer.draw();
        },

        selectElement(element) {
            this.deselectAll();
            this.selectedElement = element;

            if (!element._features && element._type === 'slot') {
                element._features = element.features || { refrigerated: false, hazmat: false, covered: false };
            }

            const id = element._type === 'zone'
                ? 'zone-' + (element.id || element._tempId)
                : 'slot-' + (element.id || element._tempId);

            const node = this.stage.findOne('#' + id);
            if (node) {
                const rect = node.findOne(element._type === 'zone' ? '.zone-rect' : '.slot-rect');
                if (rect) {
                    rect.stroke('#3B82F6');
                    rect.strokeWidth(3);
                    this.layer.draw();
                }
            }
        },

        selectZone(zone) {
            this.selectElement(zone);
        },

        deselectAll() {
            this.selectedElement = null;
            this.layer.find('.zone-rect, .slot-rect').forEach(rect => {
                if (rect.name() === 'zone-rect') {
                    const group = rect.getParent();
                    const zoneId = group.id().replace('zone-', '');
                    const zone = this.zones.find(z => String(z.id) === zoneId || z._tempId === zoneId);
                    rect.stroke(zone?.color || '#3B82F6');
                    rect.strokeWidth(2);
                } else {
                    rect.stroke('#FFFFFF');
                    rect.strokeWidth(2);
                }
            });
            this.layer.draw();
        },

        updateElement() {
            if (!this.selectedElement) return;

            const el = this.selectedElement;
            const id = el._type === 'zone'
                ? 'zone-' + (el.id || el._tempId)
                : 'slot-' + (el.id || el._tempId);

            const node = this.stage.findOne('#' + id);
            if (!node) return;

            if (el._type === 'zone') {
                const rect = node.findOne('.zone-rect');
                const label = node.findOne('.zone-label');
                const typeLabel = node.findOne('.zone-type-label');
                if (rect) {
                    rect.fill((el.color || '#3B82F6') + '20');
                    rect.stroke(el.color || '#3B82F6');
                }
                if (label) {
                    label.text(el.name || 'Zone');
                    label.fill(el.color || '#3B82F6');
                }
                if (typeLabel) {
                    typeLabel.text((el.type || '').replace('_', ' '));
                }
            } else {
                const statusColorMap = {
                    available: '#22C55E',
                    occupied: '#EF4444',
                    reserved: '#8B5CF6',
                    maintenance: '#6B7280',
                    blocked: '#1F2937',
                };
                const rect = node.findOne('.slot-rect');
                const label = node.findOne('.slot-label');
                if (rect) rect.fill(statusColorMap[el.status] || '#22C55E');
                if (label) label.text(el.slot_number || '?');

                if (el._features) {
                    el.features = { ...el._features };
                }
            }

            this.layer.draw();
        },

        updateZoneColor() {
            if (this.selectedElement && this.selectedElement._type === 'zone') {
                this.selectedElement.color = this.getZoneColor(this.selectedElement.type);
            }
        },

        deleteSelected() {
            if (!this.selectedElement) return;
            if (!confirm('Delete this ' + this.selectedElement._type + '?')) return;

            const el = this.selectedElement;
            const id = el._type === 'zone'
                ? 'zone-' + (el.id || el._tempId)
                : 'slot-' + (el.id || el._tempId);

            const node = this.stage.findOne('#' + id);
            if (node) node.destroy();

            if (el._type === 'zone') {
                this.zones = this.zones.filter(z => z !== el);
            } else {
                this.zones.forEach(z => {
                    if (z.slots) z.slots = z.slots.filter(s => s !== el);
                });
            }

            this.selectedElement = null;
            this.layer.draw();
        },

        getSlotZoneName(slot) {
            if (!slot) return '';
            const zone = this.zones.find(z => (z.slots || []).includes(slot));
            return zone ? zone.name : 'Unknown';
        },

        zoomIn() {
            this.zoomLevel = Math.min(this.zoomLevel * 1.2, 5);
            this.stage.scale({ x: this.zoomLevel, y: this.zoomLevel });
        },

        zoomOut() {
            this.zoomLevel = Math.max(this.zoomLevel / 1.2, 0.2);
            this.stage.scale({ x: this.zoomLevel, y: this.zoomLevel });
        },

        resetZoom() {
            this.zoomLevel = 1;
            this.stage.scale({ x: 1, y: 1 });
            this.stage.position({ x: 0, y: 0 });
        },

        async saveLayout() {
            this.saving = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Collect zone positions from canvas
            const zonesPayload = this.zones.map(zone => {
                const node = this.stage.findOne('#zone-' + (zone.id || zone._tempId));
                const pos = node ? node.position() : (zone.position_data || {});
                const rect = node ? node.findOne('.zone-rect') : null;

                return {
                    id: zone.id || null,
                    name: zone.name,
                    type: zone.type,
                    capacity: zone.capacity,
                    color: zone.color,
                    priority: zone.priority,
                    status: zone.status,
                    position_data: {
                        x: pos.x,
                        y: pos.y,
                        width: rect ? rect.width() : (zone.position_data?.width || 300),
                        height: rect ? rect.height() : (zone.position_data?.height || 200),
                    },
                };
            });

            const slotsPayload = [];
            this.zones.forEach((zone, zoneIndex) => {
                (zone.slots || []).forEach(slot => {
                    const slotNode = this.stage.findOne('#slot-' + (slot.id || slot._tempId));
                    const slotPos = slotNode ? slotNode.position() : (slot.position_data || {});

                    slotsPayload.push({
                        id: slot.id || null,
                        _zone_index: zoneIndex,
                        yard_zone_id: zone.id || null,
                        slot_number: slot.slot_number,
                        type: slot.type,
                        size: slot.size,
                        status: slot.status,
                        features: slot.features || slot._features || {},
                        position_data: {
                            x: slotPos.x,
                            y: slotPos.y,
                            width: slot.position_data?.width || 50,
                            height: slot.position_data?.height || 50,
                        },
                    });
                });
            });

            try {
                const response = await fetch('{{ route("admin.yards.save-layout", $yard) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        yard_layout: { version: 1, timestamp: new Date().toISOString() },
                        zones: zonesPayload,
                        slots: slotsPayload,
                    }),
                });

                const data = await response.json();
                if (data.success) {
                    alert('Layout saved successfully!');
                    window.location.reload();
                } else {
                    alert('Failed to save layout: ' + (data.message || 'Unknown error'));
                }
            } catch (err) {
                console.error(err);
                alert('Error saving layout');
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>

@endsection
