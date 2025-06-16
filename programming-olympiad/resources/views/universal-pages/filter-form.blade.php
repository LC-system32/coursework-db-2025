<div class="accordion mb-4" id="filterAccordion">
    <div class="accordion-item rounded-4 border-0 shadow-sm">
        <h2 class="accordion-header">
            <button class="accordion-button rounded-4 collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-funnel-fill me-2"></i> Фільтри
            </button>
        </h2>
        <div id="filterCollapse" class="accordion-collapse collapse" data-bs-parent="#filterAccordion">
            <div class="accordion-body">
                <form method="GET" id="filterForm">
                    <div class="row g-3">
                        @foreach($filters as $filter)
                        <div class="col-md-4">
                            <label for="{{ $filter['name'] }}" class="form-label">{{ $filter['label'] }}</label>

                            @if(in_array($filter['type'], ['text', 'number', 'date']))
                            <input type="{{ $filter['type'] }}"
                                name="{{ $filter['name'] }}"
                                id="{{ $filter['name'] }}"
                                class="form-control"
                                value="{{ $filter['value'] ?? '' }}">
                            <div class="invalid-feedback" id="error-{{ $filter['name'] }}"></div>

                            @elseif($filter['type'] === 'select' && is_array($filter['options']))
                            <select name="{{ $filter['name'] }}"
                                id="{{ $filter['name'] }}"
                                class="form-select {{ str_contains($filter['name'], '[]') ? 'multiple-select' : '' }}"
                                {{ str_contains($filter['name'], '[]') ? 'multiple' : '' }}>

                                @if (!str_contains($filter['name'], '[]'))
                                    <option value="">-- не вибрано --</option>
                                @endif

                                @foreach($filter['options'] as $key => $option)
                                    @php
                                        $isAssoc = is_string($key);
                                        $optionValue = $isAssoc ? $key : $option;
                                        $optionLabel = $isAssoc ? $option : $option;
                                        $selected = str_contains($filter['name'], '[]')
                                            ? in_array($optionValue, $filter['selected'] ?? [])
                                            : ($filter['selected'] ?? '' == $optionValue);
                                    @endphp
                                    <option value="{{ $optionValue }}" {{ $selected ? 'selected' : '' }}>
                                        {{ $optionLabel }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback" id="error-{{ $filter['name'] }}"></div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-outline-primary px-4 rounded-pill">Застосувати</button>
                    </div>
                </form>
                <div id="filterError" class="alert alert-danger d-none mt-3" role="alert"></div>
            </div>
        </div>
    </div>
</div>