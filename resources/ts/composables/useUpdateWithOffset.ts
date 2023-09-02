export default function useUpdateWithOffset<T>(
    callback: (args: T) => void,
    timeOffset = 500,
) {
    let lastChange = new Date()

    function updateWithOffset(args: T) {
        lastChange = new Date()

        setTimeout(
            () => {
                if (new Date().getTime() - lastChange.getTime() >= timeOffset) {
                    callback(args)
                }
            },
            timeOffset + 1, // +1 because it seems that sometimes it doesn't refresh correctly
        )
    }

    return {updateWithOffset}
}