import { createMongoAbility } from '@casl/ability';
import { abilitiesPlugin } from '@casl/vue';

export default function (app) {
  // const userAbilityRules = useCookie('userAbilityRules');
  const userAbilityRules = localStorage.getItem('gms_actions');
  

  const initialAbility = createMongoAbility(userAbilityRules ?? [])

  app.use(abilitiesPlugin, initialAbility, {
    useGlobalProperties: true,
  })
}
