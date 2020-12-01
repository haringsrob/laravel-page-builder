<template>
  <div
    class="mb-3 mt-3 border border-50 p-3"
  >
    <div class="flex">
      <h3>
        {{ rowConfig.label }}
      </h3>
      <span class="ml-auto">
        <delete-button @click="$emit('delete-row')" />
        <span v-if="allowMultiple">
          <button
            v-if="!isFirst"
            @click.prevent="$emit('move', 'up')"
          ><svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          ><path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"
          /></svg></button>
          <button
            v-if="!isLast"
            @click.prevent="$emit('move', 'down')"
          ><svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          ><path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"
          /></svg></button>
        </span>
      </span>
    </div>
    <div
      v-for="(fieldInfo, index) in value"
      :key="index"
    >
      <h4>{{ fieldInfo.id }}</h4>
      <!-- Simple text -->
      <template v-if="fieldInfo.type === 'text'">
        <input
          v-model="fieldInfo.content"
          class="w-full form-control form-input form-input-bordered"
          :placeholder="fieldInfo.id"
        >
      </template>
      <!-- Single child -->
      <template v-if="fieldInfo.type === 'child'">
        <select
          v-if="fieldInfo.content.name === undefined"
          class="w-full form-control form-select"
          @change="addRow($event, fieldInfo.id)"
        >
          <option
            selected="selected"
            value=""
          >
            Select a component to add
          </option>
          <option
            v-for="(metaData, fieldId) in availableComponents"
            :key="fieldId"
            :value="fieldId"
          >
            {{ metaData.label }}
          </option>
        </select>
        <!-- First nested -->
        <builder-field
          v-if="fieldInfo.content.name !== undefined"
          :key="fieldInfo.content.name"
          v-model="fieldInfo.content.fields"
          :available-components="availableComponents"
          :row-config="fieldInfo.content"
          :indentation="indentation + 1"
          :allow-multiple="false"
          @delete-row="emptyContent(fieldInfo.id)"
        />
      </template>
      <!-- Multiple children -->
      <template v-if="fieldInfo.type === 'children'">
        <select
          class="w-full form-control form-select"
          @change="addRow($event, fieldInfo.id)"
        >
          <option
            selected="selected"
            value=""
          >
            Select a component to add
          </option>
          <option
            v-for="(metaData, fieldId) in availableComponents"
            :key="fieldId"
            :value="fieldId"
          >
            {{ fieldId }}
          </option>
        </select>
        <!-- First nested -->
        <template v-for="(rowConfigChild) in fieldInfo.content">
          <builder-field
            :key="rowConfigChild.name"
            v-model="rowConfigChild.fields"
            :available-components="availableComponents"
            :row-config="rowConfigChild"
            :indentation="indentation + 1"
            :allow-multiple="true"
            @delete-row="deleteRow(index, fieldInfo.id)"
          />
        </template>
      </template>
    </div>
    <hr>
  </div>
</template>

<script>
import DeleteButton from './DeleteButton';
export default {
  name: "BuilderField",
  components: {DeleteButton},
  props: [
    "availableComponents",
    "value",
    "rowConfig",
    "indentation",
    "allowMultiple",
    "isFirst",
    "isLast",
  ],
  methods: {
    emptyContent(name) {
      var copy = this.value;
      copy[name].content[name].content = {};
      this.$emit('input', copy);
    },
    deleteRow(index, name) {
      var copy = this.value;
      this.$delete(copy[name].content, index);
      this.$emit('input', copy);
    },
    addRow(event, fieldPart) {
      if (event.target.value === "") { return; }
      var info = this.availableComponents[event.target.value];

      var copy = this.value;

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

      if (copy[fieldPart].type === 'children') {
        copy[fieldPart].content.push(pushData);
      }
      if (copy[fieldPart].type === 'child') {
        copy[fieldPart].content = pushData;
      }

      this.$emit('input', copy);

      // Reset.
      event.target.value = "";
    },
  }
}
</script>
