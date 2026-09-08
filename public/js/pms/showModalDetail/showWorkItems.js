function showItemsDetail(id) {
    const $modal = $("#ItemsModal");
    const $container = $("#modalItemsContainer");

    $container.html('<p class="text-muted small">Loading...</p>');
    $modal.show(); // Show modal immediately

    $.ajax({
        type: "GET",
        url: `/project-management/get-items-detail/${encodeURIComponent(id)}`,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            $container.empty();
            if (response.status !== "success") {
                $container.html(
                    '<div class="alert alert-danger">Failed to fetch data.</div>',
                );
                return;
            }
            const data = response.value || [];
            if (data.length === 0) {
                $container.html(
                    '<div class="alert alert-warning">No Items Found</div>',
                );
                return;
            }
            let html = `
                <div class="accordion" id="itemsAccordion">
            `;
            $.each(data, function (index, item) {
                html += `
                <div class="accordion-item border-0 shadow-sm rounded mb-3">
                    <h2 class="accordion-header" id="heading${index}">
                        <button
                            class="accordion-button collapsed fw-bold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse${index}"
                            aria-expanded="false"
                            aria-controls="collapse${index}">

                            <i class="fas fa-layer-group text-rose-primary me-2"></i>

                            Item ${index + 1} :
                            ${escapeHtml(item.name)}
                        </button>
                    </h2>
                    <div id="collapse${index}"
                        class="accordion-collapse collapse"
                        data-bs-parent="#itemsAccordion">
                        <div class="accordion-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <strong>Unit</strong>
                                    <br>
                                    ${escapeHtml(item.unit)}
                                </div>
                                <div class="col-md-3">
                                    <strong>Quantity</strong>
                                    <br>
                                    ${item.qty}
                                </div>
                            </div>
                `;

                // =======================
                // Sub Items
                // =======================

                if (item.sub_items && item.sub_items.length > 0) {
                    html += `
                        <div class="mb-4">
                            <h6 class="text-rose-primary mb-2">
                                <i class="fas fa-list me-2"></i>
                                Sub Items
                            </h6>
                    `;

                    $.each(item.sub_items, function (i, sub) {
                        html += `
                            <span class="badge rounded-pill bg-secondary me-2 mb-2 px-3 py-2">
                                ${escapeHtml(sub)}
                            </span>
                        `;
                    });
                    html += `</div>`;
                }

                // =======================
                // Work Plans
                // =======================

                if (item.work_plans && item.work_plans.length > 0) {
                    html += `
                        <h6 class="text-success mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Work Plans
                        </h6>
                    `;

                    $.each(item.work_plans, function (i, plan) {
                        html += `
                            <div class="card border-start border-4 border-success shadow-sm mb-3">
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">
                                                Start Date
                                            </small>
                                            <div>
                                                ${plan.plan_start_date ?? "-"}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">
                                                End Date
                                            </small>
                                            <div>
                                                ${plan.plan_end_date ?? "-"}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">
                                                Predecessor
                                            </small>
                                            <div>
                                               <span class="badge badge-rose-primary">
                                                    ${plan.precedence_item_name ?? "No Predecessor"}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }

                html += `
                        </div>
                    </div>
                </div>
                `;
            });

            html += `</div>`;

            $container.html(html);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            $container.html(
                '<p class="text-danger">An error occurred while loading details.</p>',
            );
        },
    });
}

// Helper function to prevent XSS
function escapeHtml(text) {
    return text
        ? String(text).replace(
              /[&<>"']/g,
              (m) =>
                  ({
                      "&": "&amp;",
                      "<": "&lt;",
                      ">": "&gt;",
                      '"': "&quot;",
                      "'": "&#039;",
                  })[m],
          )
        : "";
}

// EVENT LISTENERS (Define these ONCE outside the function)
$(document).ready(function () {
    const modal = document.getElementById("ItemsModal");

    // Close button logic
    $(".btn-close").on("click", function () {
        $("#ItemsModal").hide();
    });

    // Click outside modal logic
    $(window).on("click", function (event) {
        if (event.target === modal) {
            $(modal).hide();
        }
    });
});
