<script>
(() => {
    const key = 'lar_accessories_cart';
    const ownerKey = `${key}:owner`;
    const userId = String(@json(Auth::id()));
    const snapshotKey = `${key}:snapshot:${userId}`;
    const token = '{{ csrf_token() }}';
    let lastSynced = localStorage.getItem(snapshotKey);
    let syncQueue = Promise.resolve();
    let initialized = false;
    let pendingItems = null;

    const read = () => {
        try {
            const value = JSON.parse(localStorage.getItem(key) || '[]');
            return Array.isArray(value) ? value : [];
        } catch {
            return [];
        }
    };

    const itemKey = item => `${Number(item.id || 0)}:${Number(item.variant_id || 0)}`;

    const normalize = items => {
        const map = new Map();

        (Array.isArray(items) ? items : []).forEach(item => {
            const normalized = {
                ...item,
                id: Number(item.id || 0),
                variant_id: item.variant_id ? Number(item.variant_id) : null,
                quantity: Math.min(100000, Math.max(1, Number(item.quantity || 1))),
                checked: item.checked !== false,
            };

            if (normalized.id <= 0) return;

            const current = map.get(itemKey(normalized));
            map.set(itemKey(normalized), current
                ? {...normalized, quantity: Math.min(100000, current.quantity + normalized.quantity)}
                : normalized);
        });

        return [...map.values()];
    };

    const serialize = items => JSON.stringify(normalize(items));
    const merge = (remote, local) => normalize([...remote, ...local]);

    const request = (method, items) => fetch('{{ route("user.cart.sync") }}', {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: method === 'PUT' ? JSON.stringify({items}) : undefined,
    }).then(response => response.ok ? response.json() : null).catch(() => null);

    const remember = items => {
        const normalized = normalize(items);
        const serialized = JSON.stringify(normalized);
        localStorage.setItem(key, serialized);
        localStorage.setItem(ownerKey, userId);
        localStorage.setItem(snapshotKey, serialized);
        lastSynced = serialized;
        return normalized;
    };

    const persist = items => {
        const normalized = normalize(items);
        const serialized = JSON.stringify(normalized);

        if (!initialized) {
            pendingItems = normalized;
            return Promise.resolve(null);
        }

        if (serialized === lastSynced) return syncQueue;

        syncQueue = syncQueue.catch(() => null).then(async () => {
            const response = await request('PUT', normalized);
            if (response) remember(normalized);
            return response;
        });

        return syncQueue;
    };

    const initialize = async () => {
        const local = normalize(read());
        const remoteResponse = await request('GET');
        if (!remoteResponse) return;

        const remote = normalize(remoteResponse.items || []);
        const remoteSerialized = JSON.stringify(remote);
        const localSerialized = JSON.stringify(local);
        const owner = localStorage.getItem(ownerKey);
        let items = remote;
        let shouldPersist = false;

        if (owner === userId) {
            if (lastSynced && localSerialized !== lastSynced && remoteSerialized === lastSynced) {
                // The cart changed locally since the last successful sync.
                items = local;
                shouldPersist = true;
            } else if (lastSynced && localSerialized === lastSynced && remoteSerialized !== lastSynced) {
                // Another tab/request changed the server cart; accept the server as source of truth.
                items = remote;
            } else if (lastSynced && localSerialized !== lastSynced && remoteSerialized !== lastSynced) {
                // Concurrent changes are replaced with one exact payload; never add quantities implicitly.
                items = local;
                shouldPersist = true;
            }
        } else if (!owner) {
            // First authenticated visit: merge the guest cart exactly once.
            items = localSerialized === remoteSerialized ? remote : merge(remote, local);
            shouldPersist = localSerialized !== remoteSerialized || local.length > 0;
        }

        if (shouldPersist) {
            initialized = true;
            await persist(items);
        } else {
            remember(items);
            initialized = true;
        }

        if (pendingItems) {
            const pending = pendingItems;
            pendingItems = null;
            await persist(pending);
        }
    };

    window.syncAuthenticatedCart = items => persist(items || read());
    window.addEventListener('cart:changed', event => persist(event.detail?.items || read()));
    window.addEventListener('storage', event => {
        if (event.key === key) persist(read());
    });
    initialize();
})();
</script>
