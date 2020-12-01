<template>
  <default-field
    :field="field"
    :errors="errors"
    :show-help-text="showHelpText"
    :full-width-content="true"
  >
    <template
      slot="field"
    >
      <div class="flex">
        <svg
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        ><path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
        /></svg>
        <select
          class="w-full form-control form-select"
          @change="addRow($event)"
        >
          <option
            selected="selected"
            value=""
          >
            Select a component to add
          </option>
          <option
            v-for="(metaData, fieldId) in field.available_components"
            :key="fieldId"
            :value="fieldId"
          >
            {{ metaData.label }}
          </option>
        </select>
      </div>

      <!-- Construct the form. -->
      <template v-if="value != undefined && value.data != undefined">
        <template v-for="(rowConfig, index) in value.data">
          <builder-field
            :id="index + rowConfig.name"
            :key="index + '-' + rowConfig.name"
            v-model="rowConfig.fields"
            :available-components="field.available_components"
            :row-config="rowConfig"
            :indentation="0"
            :allow-multiple="true"
            :is-first="index === 0"
            :is-last="index === value.data.length - 1"
            @move="order($event, index)"
            @delete-row="deleteRow(index)"
          />
        </template>
      </template>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import BuilderField from './BuilderField'

export default {

  components: {BuilderField},
  mixins: [FormField, HandlesValidationErrors, BuilderField],
  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
        value: {data:[]}
    };
  },

  methods: {
    order(direction, index) {
      var copy = this.value.data;
      var itemCopy = copy[index];

      if (direction === 'up') {
        if (index > 0) {
          var prevIndex = index - 1;
          this.$set(this.value.data, index, copy[prevIndex]);
          this.$set(this.value.data, prevIndex, itemCopy);
        }
      }
      else if (direction === 'down') {
        if (index < copy.length - 1) {
          var nextIndex = index + 1;
          this.$set(this.value.data, index, copy[nextIndex]);
          this.$set(this.value.data, nextIndex, itemCopy);
        }
      }
    },
    deleteRow(index) {
        this.$delete(this.value.data, index);
    },
    addRow(event) {
      if (event.target.value === "") { return; }
      var info = this.field.available_components[event.target.value];

      var pushData = {
        name: event.target.value,
        label: info.label,
        class: info.class,
        fields: {},
      }

      for (const [id, type] of Object.entries(info.fields)) {
          if (type === 'children') {
            pushData.fields[id] = {
              id: id,
              type: type,
              content: []
            };
          }
          if (type === 'child') {
            pushData.fields[id] = {
              id: id,
              type: type,
              content: {}
            };
          }
          if (type === 'text' || type === 'wysiwyg') {
            pushData.fields[id] = {
              id: id,
              type: type,
              content: ""
            };
          }
      }

      this.value.data.push(pushData);

      // Reset.
      event.target.value = "";
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = JSON.parse(this.field.value) || null
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.value) || '')
    },
  },
}
</script>
