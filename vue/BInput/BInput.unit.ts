// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

import { shallowMount } from "@vue/test-utils";

import { Default as Story } from "./BInput.stories";
import Component from "./BInput.vue";

describe("BInput.vue", () => {
  it("functions correctly", async () => {
    const wrapper = shallowMount(Component, { propsData: Story.args });

    // When passed a value, it should be displayed in the input
    const testValue1 = "placeholder foobar";
    await wrapper.setProps({ modelValue: testValue1 });
    const input = wrapper.find("input");
    expect(input.element.value).toBe(testValue1);

    // When inputting something else, an update event should be emitted
    const testValue2 = "different string";
    await input.setValue(testValue2);
    expect(wrapper.emitted()).toHaveProperty("update:modelValue");
  });
});
