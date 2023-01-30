<!-- // Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose. -->

<template>
  <div class="mission-progress">
    <h2 class="mission-progress__title">Jouw voortgang</h2>
    <div class="mission-progress__wrapper" v-if="aMissionAvailable">
      <div class="mission-progress__mission">
        <div
          class="mission-progress__missionTitle"
          v-for="mission in availableMissions"
          :key="mission.abbreviation"
          @click="selectMission(mission)"
          :style="{
            color: selectedMission(mission) ? 'white' : mission.assets.theme.primaryColor,
            background: selectedMission(mission) ? mission.assets.theme.primaryColor : '',
            'border-color': selectedMission(mission) ? mission.assets.theme.primaryColor : '',
          }"
        >
          {{ mission.name }}
        </div>
      </div>
      <div class="mission-progress__missionProgress" v-if="chapters">
        <div class="mission-progress__chapter mission-progress__chapter--oneRow" v-if="chapters.length === 0">
          <i>Er zijn geen {{ chapterNameMultiple }} in deze missie.</i>
        </div>
        <div class="mission-progress__chapter mission-progress__chapter--titleRow" v-else>
          <span class="mission-progress__number"></span>
          <span>Hoofdstuk</span>
          <span class="mission-progress__progress"> Levels af </span>
        </div>
        <div class="mission-progress__chapter" v-for="chapter in chapters" :key="chapter.uuid">
          <span class="mission-progress__number">#{{ chapter.number }}</span>
          <span>{{ chapter.name }}</span>
          <span v-if="chapter.numberOfLevelsCompleted < chapter.numberOfLevels" class="mission-progress__progress">
            <span class="mission-progress__progress--number">{{ chapter.numberOfLevelsCompleted }}</span>
            <span class="text-primary">/</span>
            <span class="mission-progress__progress--number">{{ chapter.numberOfLevels }}</span>
          </span>
          <span v-else>
            <StarComponent :achieved="true" :size="'20px'"></StarComponent>
            <StarComponent :achieved="true" :size="'20px'"></StarComponent>
            <StarComponent :achieved="true" :size="'20px'"></StarComponent>
          </span>
        </div>
      </div>
    </div>
    <div v-else>
      <i>Geen {{ currentCourse.coursesName ? currentCourse.coursesName : "missies" }} beschikbaar</i>
    </div>
  </div>
</template>
<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import { Getter, Action } from "vuex-class";
import { MissionDetails } from "@/modules/mission/mission.types";
import StarComponent from "@module/core/components/star.vue";
import { ChapterBase } from "@module/chapter/chapter.types";
import { CourseDetails } from "@/modules/course/course.types";

@Component({
  components: {
    StarComponent,
  },
})
export default class MissionProgressComponent extends Vue {
  @Getter("currentCourse", { namespace: "course" })
  private currentCourse!: CourseDetails;
  @Getter("getMissions", { namespace: "mission" })
  private missions!: MissionDetails[];
  @Getter("getChapters", { namespace: "chapter" })
  private chapters!: ChapterBase[];
  @Getter("currentMission", { namespace: "mission" })
  private currentMission!: MissionDetails;

  @Action("SET_CURRENT_MISSION", { namespace: "mission" })
  private setCurrentMission!: (mission: MissionDetails) => void;
  @Action("LOAD_CHAPTERS", { namespace: "chapter" })
  private loadChapters!: () => void;

  private get aMissionAvailable() {
    if (this.availableMissions.length > 0) {
      return this.availableMissions[0];
    }
    return false;
  }

  private get availableMissions() {
    return this.missions.filter((m) => {
      return m.isAvailable;
    });
  }

  private get chapterNameMultiple(): string {
    return this.currentMission.chapterNaming?.chapterNameMultiple ?? "hoofdstukken";
  }

  private async beforeMount() {
    if (!this.currentMission || !this.currentMission.isAvailable) {
      if (this.aMissionAvailable) {
        this.setCurrentMission(this.aMissionAvailable);
        await this.loadChapters();
      }
    }
  }

  private selectedMission(mission: MissionDetails) {
    return this.currentMission.abbreviation.toLowerCase() === mission.abbreviation.toLowerCase();
  }

  private async selectMission(missionSelected: MissionDetails) {
    for (const mission of this.missions) {
      if (missionSelected.uuid === mission.uuid) {
        this.setCurrentMission(missionSelected);
        await this.loadChapters();
      }
    }
  }
}
</script>

<style scoped lang="scss">
.text-primary {
  color: var(--course-main);
  font-weight: bold;
}
.mission-progress {
  &__title {
    margin-bottom: 0.5 * $cs-size;
  }
  &__mission {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: 1fr;
    max-width: 95%;
    margin: 0.75 * $cs-size auto;
  }

  &__missionTitle {
    padding: 0.5em 0.375em;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.875 * $cs-size;
    letter-spacing: 0.35px;
    border: solid 1px #dbdbdb;
    transition: 0.25s border-color, 0.25s background-color;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;

    &:first-child {
      border-radius: 500px 0 0 500px;
    }
    &:last-child {
      border-radius: 0 500px 500px 0;
    }
    &:only-child {
      border-radius: 500px;
      cursor: default;
    }

    &:hover {
      border-color: #b4b4b4;
    }
  }

  &__missionProgress {
    margin: $cs-size 0;
  }

  &__chapter {
    display: grid;
    grid-template-columns: 1fr minmax(auto, 8fr) 8fr;
    margin-bottom: 0.125 * $cs-size;
    align-items: center;
    min-height: 24px;
    &--titleRow,
    &--oneRow {
      color: #6d7278;
    }
    &--oneRow {
      grid-template-columns: 1fr;
    }
  }
  &__number {
    color: var(--course-main);
  }
  &__progress {
    &--number {
      min-width: 0.7em;
      text-align: center;
      display: inline-block;
    }
  }
}
</style>
