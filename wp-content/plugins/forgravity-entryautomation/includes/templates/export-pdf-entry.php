<?php
/**
 * The export PDF entry template.
 *
 * @package ForGravity\Entry_Automation
 */

?>
		<table cellspacing="0" class="widefat fixed entry-detail-view">
			<thead>
			<tr>
				<th id="details">
					<?php

					$title = sprintf( '%s : %s %s', esc_html( $form['title'] ), esc_html__( 'Entry # ', 'gravityforms' ), absint( $entry['id'] ) ); // phpcs:ignore
					/**
					 * Filters the title displayed on the entry detail page.
					 *
					 * @since 1.9
					 *
					 * @param string $title The title used.
					 * @param array  $form  The Form Object.
					 * @param array  $entry The Entry Object.
					 */
					echo apply_filters( 'gform_entry_detail_title', $title, $form, $entry ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php

				$count          = 0;
				$field_count    = count( $fields );
				$products       = array();
				$product_fields = array();

			foreach ( $fields as $field_meta ) {

				// Skip entry notes field.
				if ( 'entry_notes' === $field_meta['id'] ) {
					continue;
				}

				// Initialize field content and value variables.
				$content = '';

				// Get field.
				$field = GFFormsModel::get_field( $form, $field_meta['id'] );

				// Bail if field doesn't exist.
				if ( ! $field ) {
					continue;
				}

				// Get field label and value.
				$label = $this->get_field_label( $form, $field_meta );

				switch ( $field->get_input_type() ) {
					case 'section':
						if ( ! GFCommon::is_section_empty( $field, $form, $entry ) ) {
							$count ++;
							$is_last = $count >= $field_count ? ' lastrow' : '';

							$content = '
	                                <tr>
	                                    <td colspan="2" class="entry-view-section-break' . $is_last . '">' . esc_html( $label ) . '</td>
	                                </tr>';
						}
						break;

					case 'captcha':
					case 'html':
					case 'password':
					case 'page':
						// Ignore captcha, html, password, page field.
						break;

					default:
						// Ignore product fields as they will be grouped together at the end of the grid.
						if ( GFCommon::is_product_field( $field->type ) ) {
							$product_fields[] = $field_meta;
							break;
						}

						// Handle input values.
						$inputs = $field->get_entry_inputs();

						if ( is_array( $inputs ) ) {
							$value         = $this->get_field_value( $form, $entry, $field_meta['id'], false );
							$display_value = $value;
						} else {
							$value         = RGFormsModel::get_lead_field_value( $entry, $field );
							$display_value = GFCommon::get_lead_field_display( $field, $value, $entry['currency'] );
						}

						/**
						 * Override the field value before it is included in the PDF export.
						 *
						 * @since 1.1.6
						 *
						 * @param string $field_value Value of the field being exported.
						 * @param array  $form        The Form object.
						 * @param string $field_id    The ID of the current field.
						 * @param array  $entry       The Entry object.
						 * @param Task   $task        Entry Automation Task meta.
						 */
						$display_value = apply_filters( 'fg_entryautomation_export_field_value', $display_value, $form, $field_meta['id'], $entry, $task );

						if ( ! empty( $display_value ) || $display_value === '0' ) {
							$count ++;
							$is_last  = $count >= count( $field_meta ) && empty( $product_fields ) ? true : false;
							$last_row = $is_last ? ' lastrow' : '';

							$display_value = empty( $display_value ) && $display_value !== '0' ? '&nbsp;' : $display_value;

							$content = '
	                                <tr>
	                                    <td colspan="2" class="entry-view-field-name">' . esc_html( $label ) . '</td>
	                                </tr>
	                                <tr>
	                                    <td colspan="2" class="entry-view-field-value' . $last_row . '">' . $display_value . '</td>
	                                </tr>';
						}
						break;
				}

				/**
				 * Filters the field content.
				 *
				 * @since 3.0.2.14 Added form and field ID modifiers.
				 *
				 * @param string $content    The field content.
				 * @param array  $field      The Field Object.
				 * @param string $value      The field value.
				 * @param int    $entry['id'] The entry ID.
				 * @param int    $form['id'] The form ID.
				 */
				$content = gf_apply_filters( array( 'gform_field_content', $form['id'], $field->id ), $content, $field, $value, $entry['id'], $form['id'] );

				echo $content; // phpcs:ignore

			}

			if ( ! empty( $product_fields ) ) {
				$products = GFCommon::get_product_fields( $form, $entry );
				if ( ! empty( $products['products'] ) ) {
					ob_start();
					?>
					<tr>
						<td colspan="2" class="entry-view-field-name"><?php echo esc_html( gf_apply_filters( array( 'gform_order_label', $form['id'] ), __( 'Order', 'gravityforms' ), $form['id'] ) ); ?></td>
					</tr>
					<tr>
						<td colspan="2" class="entry-view-field-value lastrow">
							<table class="entry-products" cellspacing="0" width="97%">
								<colgroup>
									<col class="entry-products-col1" />
									<col class="entry-products-col2" />
									<col class="entry-products-col3" />
									<col class="entry-products-col4" />
								</colgroup>
								<thead>
								<th scope="col"><?php echo gf_apply_filters( array( 'gform_product', $form['id'] ), __( 'Product', 'gravityforms' ), $form['id'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
								<th scope="col" class="textcenter"><?php echo esc_html( gf_apply_filters( array( 'gform_product_qty', $form['id'] ), __( 'Qty', 'gravityforms' ), $form['id'] ) ); ?></th>
								<th scope="col"><?php echo esc_html( gf_apply_filters( array( 'gform_product_unitprice', $form['id'] ), __( 'Unit Price', 'gravityforms' ), $form['id'] ) ); ?></th>
								<th scope="col"><?php echo esc_html( gf_apply_filters( array( 'gform_product_price', $form['id'] ), __( 'Price', 'gravityforms' ), $form['id'] ) ); ?></th>
								</thead>
								<tbody>
								<?php

								$total = 0;
								foreach ( $products['products'] as $product ) {
									?>
									<tr>
										<td>
											<div class="product_name"><?php echo esc_html( $product['name'] ); ?></div>
											<ul class="product_options">
												<?php
												$price = GFCommon::to_number( $product['price'], $entry['currency'] );
												if ( is_array( rgar( $product, 'options' ) ) ) {
													$count = count( $product['options'] );
													$index = 1;
													foreach ( $product['options'] as $option ) {
														$price += GFCommon::to_number( $option['price'], $entry['currency'] );
														$class  = $index == $count ? " class='lastitem'" : '';
														$index ++;
														?>
														<li<?php echo $class; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $option['option_label']; ?></li>
														<?php
													}
												}
												$subtotal = floatval( $product['quantity'] ) * $price;
												$total   += $subtotal;
												?>
											</ul>
										</td>
										<td class="textcenter"><?php echo esc_html( $product['quantity'] ); ?></td>
										<td><?php echo esc_html( GFCommon::to_money( $price, $entry['currency'] ) ); ?></td>
										<td><?php echo esc_html( GFCommon::to_money( $subtotal, $entry['currency'] ) ); ?></td>
									</tr>
									<?php
								}
								$total += floatval( $products['shipping']['price'] );
								?>
								</tbody>
								<tfoot>
								<?php
								if ( ! empty( $products['shipping']['name'] ) ) {
									?>
									<tr>
										<td colspan="2" rowspan="2" class="emptycell">&nbsp;</td>
										<td class="textright shipping"><?php echo esc_html( $products['shipping']['name'] ); ?></td>
										<td class="shipping_amount"><?php echo esc_html( GFCommon::to_money( $products['shipping']['price'], $entry['currency'] ) ); ?>&nbsp;</td>
									</tr>
									<?php
								}
								?>
								<tr>
									<?php
									if ( empty( $products['shipping']['name'] ) ) {
										?>
										<td colspan="2" class="emptycell">&nbsp;</td>
										<?php
									}
									?>
									<td class="textright grandtotal"><?php esc_html_e( 'Total', 'gravityforms' ); ?></td>
									<td class="grandtotal_amount"><?php echo esc_html( GFCommon::to_money( $total, $entry['currency'] ) ); ?></td>
								</tr>
								</tfoot>
							</table>
						</td>
					</tr>
					<?php
					/**
					 * Filter the markup of the order summary which appears on the Entry Detail, the {all_fields} merge tag and the {pricing_fields} merge tag.
					 *
					 * @since 3.0.2.5
					 * @see   https://www.gravityhelp.com/documentation/article/gform_order_summary/
					 *
					 * @var string $markup          The order summary markup.
					 * @var array  $form            Current form object.
					 * @var array  $entry            Current entry object.
					 * @var array  $products        Current order summary object.
					 * @var string $format          Format that should be used to display the summary ('html' or 'text').
					 */
					$order_summary = gf_apply_filters( array( 'gform_order_summary', $form['id'] ), ob_get_clean(), $form, $entry, $products, 'html' );
					echo $order_summary; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

			}

			?>
			</tbody>
		</table>
		<?php

			// Get enabled fields.
			$enabled_fields = wp_list_pluck( $fields, 'id' );

		if ( in_array( 'entry_notes', $enabled_fields ) ) {
			$notes = GFFormsModel::get_lead_notes( $entry['id'] );
			if ( ! empty( $notes ) ) {
				GFEntryDetail::notes_grid( $notes, false );
			}
		}
			echo ( $entries_processed + 1 ) < $this->found_entries ? '<div class="print-hr print-page-break"></div>' : '';
		?>
