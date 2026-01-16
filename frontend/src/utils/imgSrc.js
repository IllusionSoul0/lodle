// Get the image path locally based on champion name
export const imgSrc = (champion) => {
  const champion_name = champion
    .toLowerCase()
    .replace(/(?:^|[\s'])(\w)/g, (_, c) => c.toUpperCase())
    .replace(/[\s'.]/g, "");
  return `/champion/tiles/${champion_name}.png`;
};